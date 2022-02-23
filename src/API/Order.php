<?php

namespace BambooEcourier\API;

class Order
{
    const LABEL_FORMAT_A4 = 'A4';
    const LABEL_FORMAT_A6 = 'A6';
    const LABEL_FORMAT_A6L = 'A6l';

    /**
     * @var string $ExtOrderId
     * @access public
     */
    public $ExtOrderId = null;

    /**
     * @var string $LabelFormat
     * @access public
     */
    public $LabelFormat = self::LABEL_FORMAT_A6L;

    /**
     * @var Client $Client
     * @access public
     */
    public $Client = null;

    /**
     * @var string $Currency
     * @access public
     */
    public $Currency = null;

    /**
     * @var string $ProductClient
     * @access public
     */
    public $ProductClient = null;

    /**
     * @var string $CarType
     * @access public
     */
    public $CarType = null;

    /**
     * @var Address[] $Address
     * @access public
     */
    public $Address = [];

    /**
     * @var Package[] $Package
     * @access public
     */
    public $Package = [];

    /**
     * @var string $Content
     */
    public $Content;

    /**
     * @var string $InfoCourier
     */
    public $InfoCourier;

    /**
     * @param string $ExtOrderId
     * @param Client $Client
     * @param string $Currency
     * @param string $ProductClient
     * @param string $CarType
     * @param Address[] $Address
     * @param Package[] $Package
     * @access public
     */
    public function __construct(
        $ExtOrderId,
        $Client,
        $Currency,
        $ProductClient,
        $CarType,
        $Address = [],
        $Package = []
    ) {
        $this->ExtOrderId = $ExtOrderId;
        $this->Client = $Client;
        $this->Currency = $Currency;
        $this->ProductClient = $ProductClient;
        $this->CarType = $CarType;
        $this->Address = $Address;
        $this->Package = $Package;
    }

    /**
     * Set the value of Content
     */
    public function setContent($Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    /**
     * Set the value of InfoCourier
     */
    public function setInfoCourier($InfoCourier): self
    {
        $this->InfoCourier = $InfoCourier;

        return $this;
    }
}
