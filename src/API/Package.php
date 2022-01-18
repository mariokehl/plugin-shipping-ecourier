<?php

namespace BambooEcourier\API;

class Package
{
    /**
     * @var string $Weight
     * @access public
     */
    public $Weight = null;

    /**
     * @param string $Weight
     * @access public
     */
    public function __construct($Weight)
    {
        $this->Weight = $Weight;
    }
}
