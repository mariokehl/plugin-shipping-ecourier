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
    public $Name2;

    /**
     * @var string $Telefon
     * @access public
     */
    public $Telefon;

    /**
     * @var string $Mail
     * @access public
     */
    public $Mail;

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
     * @var string
     * @access public
     */
    public $TimeFrom;

    /**
     * @var string
     * @access public
     */
    public $TimeTo;

    /**
     * @param integer $Type
     * @param string $Name1
     * @param string $Street
     * @param string $House
     * @param string $Country
     * @param string $Zipcode
     * @param string $City
     * @param string $Date
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
        $Date
    ) {
        $this->Type     = $Type;
        $this->Name1    = $Name1;
        $this->Street   = $Street;
        $this->House    = $House;
        $this->Country  = $Country;
        $this->Zipcode  = $Zipcode;
        $this->City     = $City;
        $this->Date     = $Date;
    }

    /**
     * Set the value of Name2
     */
    public function setName2($Name2): self
    {
        $this->Name2 = $Name2;

        return $this;
    }

    /**
     * Set the value of Telefon
     */
    public function setTelefon($Telefon): self
    {
        $this->Telefon = $Telefon;

        return $this;
    }

    /**
     * Set the value of Mail
     */
    public function setMail($Mail): self
    {
        $this->Mail = $Mail;

        return $this;
    }

    /**
     * Set the value of TimeFrom
     */
    public function setTimeFrom($TimeFrom): self
    {
        $this->TimeFrom = $TimeFrom;

        return $this;
    }

    /**
     * Set the value of TimeTo
     */
    public function setTimeTo($TimeTo): self
    {
        $this->TimeTo = $TimeTo;

        return $this;
    }
}
