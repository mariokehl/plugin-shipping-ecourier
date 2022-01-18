<?php

namespace BambooEcourier\API;

class Client
{
    /**
     * @var string $ClientNumber
     * @access public
     */
    public $ClientNumber = null;

    /**
     * @param string $ClientNumber
     * @access public
     */
    public function __construct($ClientNumber)
    {
        $this->ClientNumber = $ClientNumber;
    }
}
