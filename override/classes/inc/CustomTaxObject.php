<?php
/*
*  2013
*  v1.0 - initial implementation
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  This defines a model class used by the services
*/

//----------------------------------------
// CustomTaxObject Class
//----------------------------------------

class CustomTaxObject{
	private $pst;
	private $gst;
	private $agg;
	private $name;
	private $isoCode;

	public function __construct($name, $iso)
	{
		$this->pst=0.0;
		$this->gst=0.0;
		$this->agg=0.0;
		$this->name=$name;
		$this->isoCode=$iso;
	}

	public function getPst(){
		return $this->pst;
	}

	public function setPst($pst){
		$this->pst = $pst;
	}

	public function getGst(){
		return $this->gst;
	}

	public function setGst($gst){
		$this->gst = $gst;
	}

	public function getAgg(){
		return $this->agg;
	}

	public function setAgg($agg){
		$this->agg = $agg;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getIsoCode(){
		return $this->isoCode;
	}

	public function setIsoCode($isoCode){
		$this->isoCode = $isoCode;
	}
}

?>