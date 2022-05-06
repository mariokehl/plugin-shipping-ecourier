<?php

namespace BambooEcourier\Providers;

use Plenty\Modules\Order\Shipping\ServiceProvider\Services\ShippingServiceProviderService;
use Plenty\Plugin\ServiceProvider;

/**
 * Class BambooEcourierServiceProvider
 * @package BambooEcourier\Providers
 */
class BambooEcourierServiceProvider extends ServiceProvider
{
    /**
     * Register the route service provider
     */
    public function register()
    {
        //$this->getApplication()->register(BambooEcourierRouteServiceProvider::class);
    }

    /**
     * @param ShippingServiceProviderService $shippingServiceProviderService
     * @return void
     */
    public function boot(ShippingServiceProviderService $shippingServiceProviderService)
    {
        $shippingServiceProviderService->registerShippingProvider(
            'BambooEcourier',
            [
                'de' => 'Schnittstelle eCourier (JSON)',
                'en' => 'Interface eCourier (JSON)'
            ],
            [
                'BambooEcourier\\Controllers\\ShippingController@registerShipments',
                'BambooEcourier\\Controllers\\ShippingController@getLabels'
            ]
        );
    }
}
