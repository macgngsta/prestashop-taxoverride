<?php
/*
*  2013
*  v1.0 - use interface
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  Declare some helpful interfaces
*/

//----------------------------------------
// iTaxOverrideService Class
//----------------------------------------

interface iTaxOverrideService{
	public function getTaxRate($tRequest);
}

//----------------------------------------
// DefaultTaxOverrideService Class
//----------------------------------------

class DefaultTaxOverrideService implements iTaxOverrideService{
	
	public function __construct()
	{
		$this->host="";
		$this->endpoint="";
	}

	public function getTaxRate($tRequest){
		return new TaxRateOverrideResponse();
	}
}


//----------------------------------------
// TaxRateOverrideRequest Class
//----------------------------------------

class TaxRateOverrideRequest{

	const XML_FORMAT="xml";
	const JSON_FORMAT="json";

	private $fmt;
	private $address;
	private $city;
	private $zip;
	private $state;

	//----------------------------------------
	// CONSTRUCTOR
	//----------------------------------------

	public function __construct()
	{
		$this->fmt=self::XML_FORMAT;
		$this->address="";
		$this->city="";
		$this->zip="";
		$this->state="";
	}

	//----------------------------------------
	// GETTERS SETTERS
	//----------------------------------------

	public function getFmt(){
		return $this->fmt;
	}

	public function setFmt($fmt){
		$this->fmt = $fmt;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		$this->address = $address;
	}

	public function getCity(){
		return $this->city;
	}

	public function setCity($city){
		$this->city = $city;
	}

	public function getZip(){
		return $this->zip;
	}

	public function setZip($zip){
		$this->zip = $zip;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}
}

//----------------------------------------
// TaxRateOverrideResponse CLASS
//----------------------------------------

class TaxRateOverrideResponse{

	const STATUS_INVALID_REQ="INV_REQ";
	const STATUS_SERVER_ERROR="SERVER_ERROR";
	const STATUS_SUCCESS="SUCCESS";
	const STATUS_NO_RESULTS="NO_RESULTS";
	const STATUS_INVALID_RESP="INV_RESP";

	private $locationCode;
	private $localRate;
	private $stateRate;
	private $locationName;
	private $aggregateRate;
	private $status;

	//----------------------------------------
	// CONSTRUCTOR
	//----------------------------------------

	public function __construct()
	{
		$this->locationCode="";
		$this->locationName="";
		$this->localRate=0.0;
		$this->stateRate=0.0;
		$this->aggregateRate=0.0;
		$this->status=self::STATUS_INVALID_RESP;
	}

	//----------------------------------------
	// GETTERS SETTERS
	//----------------------------------------

	public function getLocationCode(){
		return $this->locationCode;
	}

	public function setLocationCode($locationCode){
		$this->locationCode = $locationCode;
	}

	public function getLocationName(){
		return $this->locationName;
	}

	public function setLocationName($locationName){
		$this->locationName = $locationName;
	}

	public function getLocalRate(){
		return $this->localRate;
	}

	public function setLocalRate($localRate){
		$this->localRate = (double)$localRate;
	}

	public function getStateRate(){
		return $this->stateRate;
	}

	public function setStateRate($stateRate){
		$this->stateRate = (double)$stateRate;
	}

	public function getAggregateRate(){
		return $this->aggregateRate;
	}

	public function setAggregateRate($aggregateRate){
		$this->aggregateRate = (double)$aggregateRate;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	//----------------------------------------
	// HELPER
	//----------------------------------------

	public function isValid()
	{
		if($this->status == self::STATUS_SUCCESS){
			if($this->aggregateRate > 0.0 && $this->stateRate >= 0.0 && $this->localRate >= 0.0){
				return true;
			}
		}
		return false;
	}

	public function __toString(){
		return var_export($this, true);
	}
}


?>