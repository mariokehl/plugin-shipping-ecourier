<?php

namespace BambooEcourier\API;

class Address
{
    const ADDRESS_TYPE_PICKUP   = 1;
    const ADDRESS_TYPE_DELIVERY = 2;

    /**
     * @var integer $Type
     * @access public
     */
    public $Type = null;

    /**
     * @var string $Name1
     * @access public
     */
    public $Name1 = null;

    /**
     * @var string $Name2
     * @access public
     */
    public $Name2 = '';

    /**
     * @var string $Telefon
     * @access public
     */
    public $Telefon = '';

    /**
     * @var string $Mail
     * @access public
     */
    public $Mail = '';

    /**
     * @var string $Street
     * @access public
     */
    public $Street = null;

    /**
     * @var string $House
     * @access public
     */
    public $House = null;

    /**
     * @var string $Country
     * @access public
     */
    public $Country = null;

    /**
     * @var string $Zipcode
     * @access public
     */
    public $Zipcode = null;

    /**
     * @var string $City
     * @access public
     */
    public $City = null;

    /**
     * @var string $Date
     * @access public
     */
    public $Date = null;

    /**
     * @param integer $Type
     * @param string $Name1
     * @param string $Street
     * @param string $House
     * @param string $Country
     * @param string $Zipcode
     * @param string $City
     * @param string $Date
     * @param string $Name2
     * @param string $Telefon
     * @param string $Mail
     * @access public
     */
    public function __construct(
        $Type,
        $Name1,
        $Street,
        $House,
        $Country,
        $Zipcode,
        $City,
        $Date,
        $Name2 = '',
        $Telefon = '',
        $Mail = ''
    ) {
        $this->Type     = $Type;
        $this->Name1    = $Name1;
        $this->Name2    = $Name2;
        $this->Telefon  = $Telefon;
        $this->Mail     = $Mail;
        $this->Street   = $Street;
        $this->House    = $House;
        $this->Country  = $Country;
        $this->Zipcode  = $Zipcode;
        $this->City     = $City;
        $this->Date     = $Date;
    }
}
