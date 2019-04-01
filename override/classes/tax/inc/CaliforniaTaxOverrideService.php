<?php
/*
*  2013
*  v1.0 - initial implementation
*  v1.0.1 - July 1, 2017 - New Tax Rate 
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  This defines a class used to override any california tax 
* 
*/

require_once('TaxOverrideService.php');
require_once('model/CustomTaxObject.php');

//----------------------------------------
// CaliforniaTaxOverrideService Class
//----------------------------------------

class CaliforniaTaxOverrideService implements iTaxOverrideService {
	
	const STATE_CALIFORNIA="California";
	const STATE_CALIFORNIA_ISO="CA";	

	private $rateMap;
	private $logId;

	//----------------------------------------

	public function __construct($logId)
	{
		$this->rateMap = array();
		$this->buildMap();
		$this->logId=$logId;
	}

	//----------------------------------------

	private function buildMap(){
		$t1 = new CustomTaxObject(self::STATE_CALIFORNIA, self::STATE_CALIFORNIA_ISO);
		$t1->setPst(0.00);
		$t1->setGst(0.095);
		$t1->setAgg(0.095);

		$t1c= strtolower($t1->getName());
		$t1iso= strtolower($t1->getIsoCode());
		$this->rateMap[$t1c] = $t1;
		$this->rateMap[$t1iso] = $t1;
	}

	//----------------------------------------

	public function getTaxRate($tRequest){

		$tResponse = new TaxRateOverrideResponse();
		
		if($this->isTaxRequestValid($tRequest)){
			
			$toFindKey = "";

			$stateFull = $tRequest->getState();
			$stateFull = strtolower($stateFull);

			PrestaShopLogger::addLog("CaliforniaTaxOverrideService: ".$this->logId." > querying cali state = ".$stateFull, 1);

			if(array_key_exists($stateFull, $this->rateMap)){
				$toFindKey = $stateFull;
			}

			if(!empty($toFindKey)){
				$cRate = $this->rateMap[$toFindKey];

				if(!empty($cRate)){
					$tResponse->setLocationCode($cRate->getIsoCode());
					$tResponse->setAggregateRate($cRate->getAgg());
					$tResponse->setLocationName($cRate->getName());
					$tResponse->setStateRate($cRate->getGst());
					$tResponse->setLocalRate($cRate->getPst());
					$tResponse->setStatus(TaxRateOverrideResponse::STATUS_SUCCESS);

					PrestaShopLogger::addLog("CaliforniaTaxOverrideService: ".$this->logId." > found cali,usa tax = ".$cRate->getAgg(), 1);
				}
			}
			else{
				PrestaShopLogger::addLog("CaliforniaTaxOverrideService: ".$this->logId." > could not find cali,usa state = ".$stateFull, 1);
			}
		}
		
		return $tResponse;
	}

	//----------------------------------------

	private function isTaxRequestValid($tRequest){
		
		//seperate out the vars
		if(is_object($tRequest)){
			$state = $tRequest->getState();
			return true;
		}

		return false;
	}

	//----------------------------------------
}

?>