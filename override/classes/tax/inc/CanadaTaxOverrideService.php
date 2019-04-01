<?php
/*
*  2013
*  v1.0 - initial implementation
*  v1.01 - effective july 1, 2016 - New Brunswick and Newfoundland increased HST from 13% to 15%
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  This defines a class used to obtain taxrate from the following site
*  http://www.flmontreal.com/2013/events/sales-tax-rate/4852/
*/

require_once('TaxOverrideService.php');
require_once('model/CustomTaxObject.php');

//----------------------------------------
// CanadaTaxOverrideService Class
//----------------------------------------

class CanadaTaxOverrideService implements iTaxOverrideService{
	
	//all the provinces
	const PROVINCE_BRITISH_COLUMBIA="British Columbia";
	const PROVINCE_ALBERTA="Alberta";
	const PROVINCE_SASKATCHEWAN="Saskatchewan";
	const PROVINCE_MANITOBA="Manitoba";
	const PROVINCE_ONTARIO="Ontario";
	const PROVINCE_QUEBEC="Quebec";
	const PROVINCE_NEW_BRUNSWICK="New Brunswick";
	const PROVINCE_NEWFOUNDLAND="Newfoundland";
	const PROVINCE_NOVA_SCOTIA="Nova Scotia";
	const PROVINCE_PRICE_EDWARD_ISLAND="Prince Edward Island";
	const PROVINCE_NORTHWEST_TERRITORIES="Northwest Territories";
	const PROVINCE_YUKON="Yukon";

	const PROVINCE_BRITISH_COLUMBIA_ISO="BC";
	const PROVINCE_ALBERTA_ISO="AB";
	const PROVINCE_SASKATCHEWAN_ISO="SK";
	const PROVINCE_MANITOBA_ISO="MB";
	const PROVINCE_ONTARIO_ISO="ON";
	const PROVINCE_QUEBEC_ISO="QC";
	const PROVINCE_NEW_BRUNSWICK_ISO="NB";
	const PROVINCE_NEWFOUNDLAND_ISO="NL";
	const PROVINCE_NOVA_SCOTIA_ISO="NS";
	const PROVINCE_PRICE_EDWARD_ISLAND_ISO="PE";
	const PROVINCE_NORTHWEST_TERRITORIES_ISO="NT";
	const PROVINCE_YUKON_ISO="YT";

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
		$t1 = new CustomTaxObject(self::PROVINCE_BRITISH_COLUMBIA, self::PROVINCE_BRITISH_COLUMBIA_ISO);
		$t1->setPst(0.07);
		$t1->setGst(0.05);
		$t1->setAgg(0.12);

		$t1c= strtolower($t1->getIsoCode());
		$t1full= strtolower($t1->getName());
		$this->rateMap[$t1c] = $t1;
		$this->rateMap[$t1full] = $t1;

		$t2 = new CustomTaxObject(self::PROVINCE_ALBERTA, self::PROVINCE_ALBERTA_ISO);
		$t2->setPst(0.0);
		$t2->setGst(0.05);
		$t2->setAgg(0.05);

		$t2c= strtolower($t2->getIsoCode());
		$t2full= strtolower($t2->getName());
		$this->rateMap[$t2c] = $t2;
		$this->rateMap[$t2full] = $t2;

		$t3 = new CustomTaxObject(self::PROVINCE_SASKATCHEWAN, self::PROVINCE_SASKATCHEWAN_ISO);
		$t3->setPst(0.05);
		$t3->setGst(0.05);
		$t3->setAgg(0.10);

		$t3c= strtolower($t3->getIsoCode());
		$t3full= strtolower($t3->getName());
		$this->rateMap[$t3c] = $t3;
		$this->rateMap[$t3full] = $t3;

		$t4 = new CustomTaxObject(self::PROVINCE_MANITOBA, self::PROVINCE_MANITOBA_ISO);
		$t4->setPst(0.07);
		$t4->setGst(0.05);
		$t4->setAgg(0.12);

		$t4c= strtolower($t4->getIsoCode());
		$t4full= strtolower($t4->getName());
		$this->rateMap[$t4c] = $t4;
		$this->rateMap[$t4full] = $t4;

		$t5 = new CustomTaxObject(self::PROVINCE_ONTARIO, self::PROVINCE_ONTARIO_ISO);
		$t5->setPst(0.0);
		$t5->setGst(0.13);
		$t5->setAgg(0.13);

		$t5c= strtolower($t5->getIsoCode());
		$t5full= strtolower($t5->getName());
		$this->rateMap[$t5c] = $t5;
		$this->rateMap[$t5full] = $t5;

		$t6 = new CustomTaxObject(self::PROVINCE_QUEBEC, self::PROVINCE_QUEBEC_ISO);
		$t6->setPst(0.09975);
		$t6->setGst(0.05);
		$t6->setAgg(0.14975);
		
		$t6c= strtolower($t6->getIsoCode());
		$t6full= strtolower($t6->getName());
		$this->rateMap[$t6c] = $t6;
		$this->rateMap[$t6full] = $t6;

		$t7 = new CustomTaxObject(self::PROVINCE_NEW_BRUNSWICK, self::PROVINCE_NEW_BRUNSWICK_ISO);
		$t7->setPst(0.0);
		$t7->setGst(0.15);
		$t7->setAgg(0.15);

		$t7c= strtolower($t7->getIsoCode());
		$t7full= strtolower($t7->getName());
		$this->rateMap[$t7c] = $t7;
		$this->rateMap[$t7full] = $t7;

		$t8 = new CustomTaxObject(self::PROVINCE_NEWFOUNDLAND, self::PROVINCE_NEWFOUNDLAND_ISO);
		$t8->setPst(0.0);
		$t8->setGst(0.15);
		$t8->setAgg(0.15);

		$t8c= strtolower($t8->getIsoCode());
		$t8full= strtolower($t8->getName());
		$this->rateMap[$t8c] = $t8;
		$this->rateMap[$t8full] = $t8;

		$t9 = new CustomTaxObject(self::PROVINCE_NOVA_SCOTIA, self::PROVINCE_NOVA_SCOTIA_ISO);
		$t9->setPst(0.0);
		$t9->setGst(0.15);
		$t9->setAgg(0.15);

		$t9c= strtolower($t9->getIsoCode());
		$t9full= strtolower($t9->getName());
		$this->rateMap[$t9c] = $t9;
		$this->rateMap[$t9full] = $t9;

		$t10 = new CustomTaxObject(self::PROVINCE_PRICE_EDWARD_ISLAND, self::PROVINCE_PRICE_EDWARD_ISLAND_ISO);
		$t10->setPst(0.0);
		$t10->setGst(0.14);
		$t10->setAgg(0.14);

		$t10c= strtolower($t10->getIsoCode());
		$t10full= strtolower($t10->getName());
		$this->rateMap[$t10c] = $t10;
		$this->rateMap[$t10full] = $t10;

		$t11 = new CustomTaxObject(self::PROVINCE_NORTHWEST_TERRITORIES, self::PROVINCE_NORTHWEST_TERRITORIES_ISO);
		$t11->setPst(0.0);
		$t11->setGst(0.05);
		$t11->setAgg(0.05);
		
		$t11c= strtolower($t11->getIsoCode());
		$t11full= strtolower($t11->getName());
		$this->rateMap[$t11c] = $t11;
		$this->rateMap[$t11full] = $t11;

		$t12 = new CustomTaxObject(self::PROVINCE_YUKON, self::PROVINCE_YUKON_ISO);
		$t12->setPst(0.0);
		$t12->setGst(0.05);
		$t12->setAgg(0.05);

		$t12c= strtolower($t12->getIsoCode());
		$t12full= strtolower($t12->getName());
		$this->rateMap[$t12c] = $t12;
		$this->rateMap[$t12full] = $t12;
	}

	//----------------------------------------

	private function isTaxRequestValid($tRequest){
		
		if(is_object($tRequest)){
			$state = $tRequest->getState();
			if(!empty($state)){
				return true;
			}
		}

		return false;
	}

	//----------------------------------------

	public function getTaxRate($tRequest){

		$tResponse = new TaxRateOverrideResponse();
		
		if($this->isTaxRequestValid($tRequest)){
			
			$toFindKey = "";

			$stateFull = $tRequest->getState();
			$stateFull = strtolower($stateFull);

			PrestaShopLogger::addLog("CanadaTaxOverrideService: ".$this->logId." > querying canada province = ".$stateFull, 1);

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

					//set default values - overall 13%
					//$tResponse->setAggregateRate(0.13);
					//$tResponse->setStateRate(0.13);
					//$tResponse->setLocalRate(0.0);

					$tResponse->setStatus(TaxRateOverrideResponse::STATUS_SUCCESS);

					PrestaShopLogger::addLog("CanadaTaxOverrideService: ".$this->logId." > found canada tax = ".$cRate->getAgg(), 1);
				}
			}
			else{
				PrestaShopLogger::addLog("CanadaTaxOverrideService: ".$this->logId." > could not find canada province = ".$stateFull, 1);
			}
		}

		return $tResponse;
	}

	//----------------------------------------
	
}

?>