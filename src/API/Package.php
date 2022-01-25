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
     * @var string $Length
     * @access public
     */
    public $Length = null;

    /**
     * @var string $Width
     * @access public
     */
    public $Width = null;

    /**
     * @var string $Height
     * @access public
     */
    public $Height = null;

     /**
      * @param string $Weight
      * @param string $Length
      * @param string $Width
      * @param string $Height
      */
    public function __construct($Weight, $Length, $Width, $Height)
    {
        $this->Weight = $Weight;
        $this->Length = $Length;
        $this->Width = $Width;
        $this->Height = $Height;
    }
}
