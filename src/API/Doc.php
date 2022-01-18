<?php

namespace BambooEcourier\API;

class Doc implements \JsonSerializable
{
    /**
     * @var Order $Order
     * @access public
     */
    public $Order = null;

    /**
     * @var integer $Id
     * @access public
     */
    public $Id = null;

    /**
     * @param integer $Id
     * @param Order $Order
     * @access public
     */
    public function __construct($Id, $Order)
    {
        $this->Id = $Id;
        $this->Order = $Order;
    }

    /**
     * @return object
     */
    public function jsonSerialize()
    {
        return (object) [
            'Doc' => [
                'Id' => $this->Id,
                'Order' => $this->Order
            ]
        ];
    }
}
