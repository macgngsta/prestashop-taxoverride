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
*  This defines a class used to override any california tax 
*/

require_once('TaxOverrideService.php');
require_once('CustomTaxObject.php');

//----------------------------------------
// CaliforniaTaxOverrideService Class
//----------------------------------------

class CaliforniaTaxOverrideService implements iTaxOverrideService {
	
	const STATE_CALIFORNIA="California";
	const STATE_CALIFORNIA_ISO="CA";	

	private $rateMap;

	public function __construct()
	{
		$this->rateMap = array();
		$this->buildMap();
	}

	private function buildMap(){
		$t1 = new CustomTaxObject(self::STATE_CALIFORNIA, self::STATE_CALIFORNIA_ISO);
		$t1->setPst(0.00);
		$t1->setGst(0.09);
		$t1->setAgg(0.09);

		$t1c= $t1->getIsoCode();
		$this->rateMap[$t1c] = $t1;
	}

	public function getTaxRate($tRequest){

		$tResponse = new TaxRateOverrideResponse();
		
		if($this->isTaxRequestValid($tRequest)){
			$s = $tRequest->getState();
			if(!empty($s)){
				$cRate = $this->rateMap[$s];

				if(!empty($cRate)){
					$tResponse->setLocationCode($cRate->getIsoCode());
					$tResponse->setAggregateRate($cRate->getAgg());
					$tResponse->setLocationName($cRate->getName());
					$tResponse->setStateRate($cRate->getGst());
					$tResponse->setLocalRate($cRate->getPst());
					$tResponse->setStatus(TaxRateOverrideResponse::STATUS_SUCCESS);
				}
			}
		}
		
		return $tResponse;
	}

	private function isTaxRequestValid($tRequest){
		
		//seperate out the vars
		if(is_object($tRequest)){
			$state = $tRequest->getState();
			return true;
		}

		return false;
	}
}

?>