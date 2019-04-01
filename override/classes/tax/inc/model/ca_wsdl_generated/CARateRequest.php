<?php

class CARateRequest
{

    /**
     * @var string $City
     */
    protected $City = null;

    /**
     * @var float $Latitude
     */
    protected $Latitude = null;

    /**
     * @var float $Longitude
     */
    protected $Longitude = null;

    /**
     * @var string $State
     */
    protected $State = null;

    /**
     * @var string $StreetAddress
     */
    protected $StreetAddress = null;

    /**
     * @var string $Token
     */
    protected $Token = null;

    /**
     * @var int $ZipCode
     */
    protected $ZipCode = null;

    /**
     * @var int $ZipCodePlusFour
     */
    protected $ZipCodePlusFour = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getCity()
    {
      return $this->City;
    }

    /**
     * @param string $City
     * @return CARateRequest
     */
    public function setCity($City)
    {
      $this->City = $City;
      return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
      return $this->Latitude;
    }

    /**
     * @param float $Latitude
     * @return CARateRequest
     */
    public function setLatitude($Latitude)
    {
      $this->Latitude = $Latitude;
      return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
      return $this->Longitude;
    }

    /**
     * @param float $Longitude
     * @return CARateRequest
     */
    public function setLongitude($Longitude)
    {
      $this->Longitude = $Longitude;
      return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
      return $this->State;
    }

    /**
     * @param string $State
     * @return CARateRequest
     */
    public function setState($State)
    {
      $this->State = $State;
      return $this;
    }

    /**
     * @return string
     */
    public function getStreetAddress()
    {
      return $this->StreetAddress;
    }

    /**
     * @param string $StreetAddress
     * @return CARateRequest
     */
    public function setStreetAddress($StreetAddress)
    {
      $this->StreetAddress = $StreetAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
      return $this->Token;
    }

    /**
     * @param string $Token
     * @return CARateRequest
     */
    public function setToken($Token)
    {
      $this->Token = $Token;
      return $this;
    }

    /**
     * @return int
     */
    public function getZipCode()
    {
      return $this->ZipCode;
    }

    /**
     * @param int $ZipCode
     * @return CARateRequest
     */
    public function setZipCode($ZipCode)
    {
      $this->ZipCode = $ZipCode;
      return $this;
    }

    /**
     * @return int
     */
    public function getZipCodePlusFour()
    {
      return $this->ZipCodePlusFour;
    }

    /**
     * @param int $ZipCodePlusFour
     * @return CARateRequest
     */
    public function setZipCodePlusFour($ZipCodePlusFour)
    {
      $this->ZipCodePlusFour = $ZipCodePlusFour;
      return $this;
    }

}
