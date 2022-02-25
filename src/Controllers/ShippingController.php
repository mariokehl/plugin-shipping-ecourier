<?php

namespace BambooEcourier\Controllers;

use Plenty\Modules\Account\Address\Models\Address;
use Plenty\Modules\Cloud\Storage\Models\StorageObject;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Order\Shipping\Contracts\ParcelServicePresetRepositoryContract;
use Plenty\Modules\Order\Shipping\Information\Contracts\ShippingInformationRepositoryContract;
use Plenty\Modules\Order\Shipping\Package\Contracts\OrderShippingPackageRepositoryContract;
use Plenty\Modules\Order\Shipping\PackageType\Contracts\ShippingPackageTypeRepositoryContract;
use Plenty\Modules\Order\Shipping\ParcelService\Models\ParcelServicePreset;
use Plenty\Modules\Plugin\Storage\Contracts\StorageRepositoryContract;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Log\Loggable;
use BambooEcourier\API\EcourierWS;
use BambooEcourier\API\Address as EcourierAddress;
use BambooEcourier\API\Package as EcourierPackage;
use BambooEcourier\API\Client as EcourierClient;
use BambooEcourier\API\Order as EcourierOrder;
use BambooEcourier\API\Doc as EcourierDoc;

/**
 * Class ShippingController
 */
class ShippingController extends Controller
{
	use Loggable;

	/**
	 * @var OrderRepositoryContract $orderRepository
	 */
	private $orderRepository;

	/**
	 * @var OrderShippingPackageRepositoryContract $orderShippingPackage
	 */
	private $orderShippingPackage;

	/**
	 * @var ShippingInformationRepositoryContract $shippingInformationRepositoryContract
	 */
	private $shippingInformationRepositoryContract;

	/**
	 * @var StorageRepositoryContract $storageRepository
	 */
	private $storageRepository;

	/**
	 * @var ShippingPackageTypeRepositoryContract $shippingPackageTypeRepositoryContract
	 */
	private $shippingPackageTypeRepositoryContract;

	/**
	 * @var ConfigRepository $config
	 */
	private $config;

	/**
	 * @var EcourierWS $webservice
	 */
	private $webservice;

	/**
	 * @var array $createOrderResult
	 */
	private $createOrderResult = [];

	/**
	 * Shipment constants
	 */
	const DEFAULT_PACKAGE_NAME = 'Wareninhalt';
	const MINIMUM_FALLBACK_WEIGHT = 0.2;

	/**
	 * Plugin key
	 */
	const PLUGIN_KEY = 'BambooEcourier';

	/**
	 * ShipmentController constructor.
	 *
	 * @param OrderRepositoryContract $orderRepository
	 * @param OrderShippingPackageRepositoryContract $orderShippingPackage
	 * @param StorageRepositoryContract $storageRepository
	 * @param ShippingInformationRepositoryContract $shippingInformationRepositoryContract
	 * @param ShippingPackageTypeRepositoryContract $shippingPackageTypeRepositoryContract
	 * @param ConfigRepository $config
	 */
	public function __construct(
		OrderRepositoryContract $orderRepository,
		OrderShippingPackageRepositoryContract $orderShippingPackage,
		StorageRepositoryContract $storageRepository,
		ShippingInformationRepositoryContract $shippingInformationRepositoryContract,
		ShippingPackageTypeRepositoryContract $shippingPackageTypeRepositoryContract,
		ConfigRepository $config
	) {
		$this->orderRepository = $orderRepository;
		$this->orderShippingPackage = $orderShippingPackage;
		$this->storageRepository = $storageRepository;

		$this->shippingInformationRepositoryContract = $shippingInformationRepositoryContract;
		$this->shippingPackageTypeRepositoryContract = $shippingPackageTypeRepositoryContract;

		$this->config = $config;

		// Get credentials by UI config
		$partnerBaseUri = $this->config->get('BambooEcourier.global.baseUri');
		$partnerApiKey 	= $this->config->get('BambooEcourier.global.apiKey');

		$this->webservice = pluginApp(EcourierWS::class, [
			$partnerBaseUri,
			[
				'apiKey' => $partnerApiKey
			],
			$this->config->get('BambooEcourier.global.mode') === 'Testing' // Demo
		]);
	}


	/**
	 * Registers shipment(s)
	 *
	 * @param Request $request
	 * @param array $orderIds
	 * @internal see BambooEcourierServiceProvider
	 * @return string
	 */
	public function registerShipments(Request $request, $orderIds)
	{
		$orderIds = $this->getOrderIds($request, $orderIds);
		$orderIds = $this->getOpenOrderIds($orderIds);
		$shipmentDate = date('Y-m-d');

		// reads sender data from plugin config
		$senderName = $this->config->get('BambooEcourier.sender.senderName', 'bamboo Software OHG');
		$senderStreet = $this->config->get('BambooEcourier.sender.senderStreet', 'Helmholtzstrasse');
		$senderNo = $this->config->get('BambooEcourier.sender.senderNo', '2-9');
		$senderCountry = $this->config->get('BambooEcourier.sender.senderCountry', 'DE');
		$senderPostalCode = $this->config->get('BambooEcourier.sender.senderPostalCode', '10587');
		$senderTown = $this->config->get('BambooEcourier.sender.senderTown', 'Berlin');

		/** @var EcourierAddress $senderAddress */
		$senderAddress = pluginApp(EcourierAddress::class, [
			EcourierAddress::ADDRESS_TYPE_PICKUP,
			$senderName,
			$senderStreet,
			$senderNo,
			$senderCountry,
			$senderPostalCode,
			$senderTown,
			$shipmentDate
		]);
		$senderAddress->setTimeFrom($this->config->get('BambooEcourier.shipping.pickupTimeFrom', '00:00:00'));
		$senderAddress->setTimeTo($this->config->get('BambooEcourier.shipping.pickupTimeTo', '00:00:00'));

		foreach ($orderIds as $orderId) {
			$order = $this->orderRepository->findOrderById($orderId);
			$this->getLogger(__METHOD__)->debug('BambooEcourier::Plenty.Order', ['order' => json_encode($order)]);

			// gathering required data for registering the shipment

			/** @var Address $address */
			$address = $order->deliveryAddress;

			$receiverName1 = implode(' ', [$address->firstName, $address->lastName]);
			$receiverName2 = '';
			if (strlen($address->companyName)) {
				$receiverName2 = $receiverName1;
				$receiverName1 = $address->companyName;
			}
			$receiverStreet	= $address->street;
			$receiverNo	= $address->houseNumber;
			$receiverCountry = $address->country->isoCode2;
			$receiverPostalCode = $address->postalCode;
			$receiverTown = $address->town;
			$receiverEmail = $address->email;
			$receiverPhone = $address->phone;

			/** @var EcourierAddress $receiverAddress */
			$receiverAddress = pluginApp(EcourierAddress::class, [
				EcourierAddress::ADDRESS_TYPE_DELIVERY,
				$receiverName1,
				$receiverStreet,
				$receiverNo,
				$receiverCountry,
				$receiverPostalCode,
				$receiverTown,
				date('Y-m-d', strtotime('tomorrow'))
			]);
			$receiverAddress->setTimeFrom($this->config->get('BambooEcourier.shipping.deliveryTimeFrom', '00:00:00'));
			$receiverAddress->setTimeTo($this->config->get('BambooEcourier.shipping.deliveryTimeTo', '00:00:00'));
			$receiverAddress->setName2($receiverName2);
			$receiverAddress->setTelefon($receiverPhone);
			$receiverAddress->setMail($receiverEmail);

			// gets order shipping packages from current order
			$packages = $this->orderShippingPackage->listOrderShippingPackages($order->id);

			// package sums
			$firstPackage = [
				'id'   => null,
				'name' => self::DEFAULT_PACKAGE_NAME
			];

			// The API packages
			$parcelData = [];

			// iterating through packages
			foreach ($packages as $key => $package) {

				// determine packageType
				$packageType = $this->shippingPackageTypeRepositoryContract->findShippingPackageTypeById($package->packageId);

				// save essentials for order level
				if (count($parcelData) === 0) {
					$firstPackage['id'] = $package->id;
					$firstPackage['name'] = $packageType->name;
				}

				// weight
				if ($package->weight) {
					$packageWeight = $package->weight / 1000;
				} else {
					$packageWeight = self::MINIMUM_FALLBACK_WEIGHT;
				}

				// package dimensions
				list($length, $width, $height) = $this->getPackageDimensions($packageType);

				$parcelData[] = pluginApp(EcourierPackage::class, [
					number_format($packageWeight, 2, '.', ''),
					'' . $length,
					'' . $width,
					'' . $height
				]);
			}

			// overwrite default delivery notice from comments (must contain @ecourier)
			$deliveryNotice = $this->config->get('BambooEcourier.shipping.deliveryNotice', '');
			/** @var Comment $comment */
			foreach ($order->comments as $comment) {
				if (!$comment->userId || !stripos($comment->text, '@ecourier')) {
					continue;
				} else {
					$commentText = strip_tags($comment->text);
					$commentText = str_replace('@ecourier', '', $commentText);
					$commentText = trim($commentText);
					$commentText = substr($commentText, 0, 255);
					$deliveryNotice = $commentText;
					break;
				}
			}
			$receiverAddress->setAddressInfo($deliveryNotice);

			// short delivery notice
			$shortDeliveryNotice = $this->config->get('BambooEcourier.shipping.shortDeliveryNotice', '');
			$receiverAddress->setName3($shortDeliveryNotice);

			// customer reference
			$ExtOrderId = $this->config->get('BambooEcourier.webservice.extOrderId', '');
			$ExtOrderId = str_replace('<tstamp>', time(), $ExtOrderId);
			$ExtOrderId = str_replace('<n>', str_pad($orderId, 12, '0', STR_PAD_LEFT), $ExtOrderId);

			// register shipment
			$containerDoc = $this->prepareDocumentForEcourier(
				$ExtOrderId,
				$senderAddress,
				$receiverAddress,
				$parcelData,
				$firstPackage['name'],
				$deliveryNotice
			);
			$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.SendungsDaten', ['Doc' => json_encode($containerDoc)]);
			$response = $this->webservice->EcourierWS_CreateOrder($containerDoc);

			if (
				!$response ||
				(is_array($response) && isset($response['error_msg']))
			) {
				$this->getLogger(__METHOD__)->error('BambooEcourier::Webservice.WSerr', ['response' => json_encode($response)]);
				continue;
			} else {
				$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.SendungsErstellung', ['response' => json_encode($response)]);
			}

			$shipmentItems = [];
			if (isset($response['Doc']['Order'][0]['HWB'])) {
				$label = $response['Doc']['Order'][0]['Label'];
				$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.PDFs', ['label' => $label]);

				// handles the response
				$shipmentItems = $this->handleAfterRegisterShipment($response, $firstPackage['id']);

				// adds result
				$this->createOrderResult[$orderId] = $this->buildResultArray(
					true,
					'',
					false,
					$shipmentItems
				);

				// saves shipping information
				$this->saveShippingInformation($orderId, $shipmentDate, $shipmentItems);
			} else {
				$this->createOrderResult[$orderId] = $this->buildResultArray(
					false,
					$response['Doc']['Error']['message'],
					false,
					$shipmentItems
				);
			}
		}

		// return all results to service
		return $this->createOrderResult;
	}

	/**
	 * Cancels registered shipment(s)
	 *
	 * @param Request $request
	 * @param array $orderIds
	 * @deprecated Unused right now
	 * @internal see BambooEcourierServiceProvider
	 * @return array
	 */
	public function deleteShipments(Request $request, $orderIds)
	{
		$orderIds = $this->getOrderIds($request, $orderIds);
		foreach ($orderIds as $orderId) {
			$shippingInformation = $this->shippingInformationRepositoryContract->getShippingInformationByOrderId($orderId);

			if (isset($shippingInformation->additionalData) && is_array($shippingInformation->additionalData)) {
				foreach ($shippingInformation->additionalData as $additionalData) {
					try {
						$shipmentNumber = $additionalData['shipmentNumber'];

						// use the shipping service provider's API here
						$response = '';

						$this->createOrderResult[$orderId] = $this->buildResultArray(
							true,
							'shipment deleted',
							false,
							null
						);
					} catch (\Exception $e) {
						// exception handling
					}
				}

				// resets the shipping information of current order
				$this->shippingInformationRepositoryContract->resetShippingInformation($orderId);
			}
		}

		// return result array
		return $this->createOrderResult;
	}


	/**
	 * Retrieves the label file from PDFs response and saves it in S3 storage
	 *
	 * @param string $label
	 * @param string $key
	 * @return StorageObject
	 */
	private function saveLabelToS3($label, $key)
	{
		return $this->storageRepository->uploadObject(self::PLUGIN_KEY, $key, $label);
	}

	/**
	 * Returns the parcel service preset for the given Id.
	 *
	 * @param int $parcelServicePresetId
	 * @return ParcelServicePreset
	 */
	private function getParcelServicePreset($parcelServicePresetId)
	{
		/** @var ParcelServicePresetRepositoryContract $parcelServicePresetRepository */
		$parcelServicePresetRepository = pluginApp(ParcelServicePresetRepositoryContract::class);

		$parcelServicePreset = $parcelServicePresetRepository->getPresetById($parcelServicePresetId);

		if ($parcelServicePreset) {
			return $parcelServicePreset;
		} else {
			return null;
		}
	}

	/**
	 * Saves the shipping information
	 *
	 * @param $orderId
	 * @param $shipmentDate
	 * @param $shipmentItems
	 */
	private function saveShippingInformation($orderId, $shipmentDate, $shipmentItems)
	{
		$transactionIds = [];
		foreach ($shipmentItems as $shipmentItem) {
			$transactionIds[] = $shipmentItem['shipmentNumber'];
		}

		$shipmentAt = date(\DateTime::W3C, strtotime($shipmentDate));
		$registrationAt = date(\DateTime::W3C);

		$data = [
			'orderId' => $orderId,
			'transactionId' => implode(',', $transactionIds),
			'shippingServiceProvider' => self::PLUGIN_KEY,
			'shippingStatus' => 'registered',
			'shippingCosts' => 0.00,
			'additionalData' => $shipmentItems,
			'registrationAt' => $registrationAt,
			'shipmentAt' => $shipmentAt

		];
		$this->shippingInformationRepositoryContract->saveShippingInformation($data);
	}

	/**
	 * Returns all order ids with shipping status 'open'
	 *
	 * @param array $orderIds
	 * @return array
	 */
	private function getOpenOrderIds($orderIds)
	{
		$openOrderIds = [];
		foreach ($orderIds as $orderId) {
			$shippingInformation = $this->shippingInformationRepositoryContract->getShippingInformationByOrderId($orderId);
			if ($shippingInformation->shippingStatus == null || $shippingInformation->shippingStatus == 'open') {
				$openOrderIds[] = $orderId;
			}
		}
		return $openOrderIds;
	}

	/**
	 * Returns an array in the structure demanded by plenty service
	 *
	 * @param bool $success
	 * @param string $statusMessage
	 * @param bool $newShippingPackage
	 * @param array $shipmentItems
	 * @return array
	 */
	private function buildResultArray($success = false, $statusMessage = '', $newShippingPackage = false, $shipmentItems = [])
	{
		return [
			'success' => $success,
			'message' => $statusMessage,
			'newPackagenumber' => $newShippingPackage,
			'packages' => $shipmentItems,
		];
	}

	/**
	 * Returns shipment array
	 *
	 * @param string $labelUrl
	 * @param string $shipmentNumber
	 * @return array
	 */
	private function buildShipmentItems($labelUrl, $shipmentNumber)
	{
		return  [
			'labelUrl' => $labelUrl,
			'shipmentNumber' => $shipmentNumber,
		];
	}

	/**
	 * Returns package info
	 *
	 * @param string $packageNumber
	 * @param string $labelUrl
	 * @return array
	 */
	private function buildPackageInfo($packageNumber, $labelUrl)
	{
		return [
			'packageNumber' => $packageNumber,
			'label' => $labelUrl
		];
	}

	/**
	 * Returns all order ids from request object
	 *
	 * @param Request $request
	 * @param $orderIds
	 * @return array
	 */
	private function getOrderIds(Request $request, $orderIds)
	{
		if (is_numeric($orderIds)) {
			$orderIds = [$orderIds];
		} else if (!is_array($orderIds)) {
			$orderIds = $request->get('orderIds');
		}
		return $orderIds;
	}

	/**
	 * Returns the package dimensions by package type
	 *
	 * @param $packageType
	 * @return array
	 */
	private function getPackageDimensions($packageType): array
	{
		if ($packageType->length > 0 && $packageType->width > 0 && $packageType->height > 0) {
			$length = $packageType->length;
			$width = $packageType->width;
			$height = $packageType->height;
		} else {
			$length = null;
			$width = null;
			$height = null;
		}
		return [$length, $width, $height];
	}

	/**
	 * Retrieve labels from S3 storage
	 * 
	 * @param Request $request
	 * @param array $orderIds
	 * @internal see BambooEcourierServiceProvider
	 * @return array
	 */
	public function getLabels(Request $request, $orderIds)
	{
		$orderIds = $this->getOrderIds($request, $orderIds);

		$labels = [];

		foreach ($orderIds as $orderId) {
			$results = $this->orderShippingPackage->listOrderShippingPackages($orderId);
			/** @var OrderShippingPackage $result */
			foreach ($results as $result) {
				if (!strlen($result->labelPath)) {
					continue;
				}
				$labelKey = explode('/', $result->labelPath)[1];
				$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.S3Storage', ['labelKey' => $labelKey]);

				if ($this->storageRepository->doesObjectExist(self::PLUGIN_KEY, $labelKey)) {
					$storageObject = $this->storageRepository->getObject(self::PLUGIN_KEY, $labelKey);
					$labels[] = $storageObject->body;
				}
			}
		}
		return $labels;
	}

	/**
	 * Handling of response values, fires S3 storage and updates order shipping package
	 *
	 * @param object $response
	 * @param integer $packageId
	 * @return array
	 */
	private function handleAfterRegisterShipment($response, $packageId)
	{
		$shipmentItems = [];

		$shipmentData = array_shift($response['Doc']['Order']);

		if (strlen($shipmentData['HWB']) > 0 && isset($shipmentData['Label'])) {
			$shipmentNumber = $shipmentData['HWB'];
			$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.S3Storage', ['length' => strlen($shipmentData['Label'])]);
			$storageObject = $this->saveLabelToS3(
				base64_decode($shipmentData['Label']),
				$packageId . '.pdf'
			);
			$this->getLogger(__METHOD__)->debug('BambooEcourier::Webservice.S3Storage', ['storageObject' => json_encode($storageObject)]);

			$shipmentItems[] = $this->buildShipmentItems(
				'path_to_pdf_in_S3',
				$shipmentNumber
			);

			$this->orderShippingPackage->updateOrderShippingPackage(
				$packageId,
				$this->buildPackageInfo($shipmentNumber, $storageObject->key)
			);
		}
		return $shipmentItems;
	}

	/**
	 * Prepare data for transport as JSON via the interface
	 *
	 * @param string $ExtOrderId
	 * @param EcourierAddress $senderAddress
	 * @param EcourierAddress $receiverAddress
	 * @param array $parcelData
	 * @param string $Content
	 * @param string $InfoCourier
	 * @return EcourierDoc
	 */
	private function prepareDocumentForEcourier(
		$ExtOrderId,
		$senderAddress,
		$receiverAddress,
		$parcelData,
		$Content = '',
		$InfoCourier = ''
	) {
		/** @var EcourierClient $WSClient */
		$WSClient = pluginApp(EcourierClient::class, [
			$this->config->get('BambooEcourier.webservice.clientNumber', '25209')
		]);

		/** @var EcourierOrder $Order */
		$Order = pluginApp(EcourierOrder::class, [
			$ExtOrderId,
			$WSClient,
			'EUR',
			$this->config->get('BambooEcourier.webservice.productClient', '550214'),
			$this->config->get('BambooEcourier.webservice.carType', ''),
			[
				$senderAddress,
				$receiverAddress
			],
			$parcelData
		]);
		$Order->setContent($Content);
		$Order->setInfoCourier($InfoCourier);

		/** @var EcourierDoc $Doc */
		$Doc = pluginApp(EcourierDoc::class, [time(), $Order]);

		return $Doc;
	}
}
