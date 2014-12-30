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
*  This defines a class used to obtain taxrate from the following site
*  http://www.flmontreal.com/2013/events/sales-tax-rate/4852/
*/

require_once('TaxOverrideService.php');
require_once('CustomTaxObject.php');

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

	public function __construct()
	{
		$this->rateMap = array();
		$this->buildMap();
	}

	private function buildMap(){
		$t1 = new CustomTaxObject(self::PROVINCE_BRITISH_COLUMBIA, self::PROVINCE_BRITISH_COLUMBIA_ISO);
		$t1->setPst(0.07);
		$t1->setGst(0.05);
		$t1->setAgg(0.12);

		$t1c= $t1->getIsoCode();
		$this->rateMap[$t1c] = $t1;

		$t2 = new CustomTaxObject(self::PROVINCE_ALBERTA, self::PROVINCE_ALBERTA_ISO);
		$t2->setPst(0.0);
		$t2->setGst(0.05);
		$t2->setAgg(0.05);

		$t2c= $t2->getIsoCode();
		$this->rateMap[$t2c] = $t2;

		$t3 = new CustomTaxObject(self::PROVINCE_SASKATCHEWAN, self::PROVINCE_SASKATCHEWAN_ISO);
		$t3->setPst(0.05);
		$t3->setGst(0.05);
		$t3->setAgg(0.10);

		$t3c= $t3->getIsoCode();
		$this->rateMap[$t3c] = $t3;

		$t4 = new CustomTaxObject(self::PROVINCE_MANITOBA, self::PROVINCE_MANITOBA_ISO);
		$t4->setPst(0.07);
		$t4->setGst(0.05);
		$t4->setAgg(0.12);

		$t4c= $t4->getIsoCode();
		$this->rateMap[$t4c] = $t4;

		$t5 = new CustomTaxObject(self::PROVINCE_ONTARIO, self::PROVINCE_ONTARIO_ISO);
		$t5->setPst(0.0);
		$t5->setGst(0.13);
		$t5->setAgg(0.13);

		$t5c= $t5->getIsoCode();
		$this->rateMap[$t5c] = $t5;

		$t6 = new CustomTaxObject(self::PROVINCE_QUEBEC, self::PROVINCE_QUEBEC_ISO);
		$t6->setPst(0.09975);
		$t6->setGst(0.05);
		$t6->setAgg(0.14975);
		
		$t6c= $t6->getIsoCode();
		$this->rateMap[$t6c] = $t6;

		$t7 = new CustomTaxObject(self::PROVINCE_NEW_BRUNSWICK, self::PROVINCE_NEW_BRUNSWICK_ISO);
		$t7->setPst(0.0);
		$t7->setGst(0.13);
		$t7->setAgg(0.13);

		$t7c= $t7->getIsoCode();
		$this->rateMap[$t7c] = $t7;

		$t8 = new CustomTaxObject(self::PROVINCE_NEWFOUNDLAND, self::PROVINCE_NEWFOUNDLAND_ISO);
		$t8->setPst(0.0);
		$t8->setGst(0.13);
		$t8->setAgg(0.13);

		$t8c= $t8->getIsoCode();
		$this->rateMap[$t8c] = $t8;

		$t9 = new CustomTaxObject(self::PROVINCE_NOVA_SCOTIA, self::PROVINCE_NOVA_SCOTIA_ISO);
		$t9->setPst(0.0);
		$t9->setGst(0.15);
		$t9->setAgg(0.15);

		$t9c= $t9->getIsoCode();
		$this->rateMap[$t9c] = $t9;

		$t10 = new CustomTaxObject(self::PROVINCE_PRICE_EDWARD_ISLAND, self::PROVINCE_PRICE_EDWARD_ISLAND_ISO);
		$t10->setPst(0.0);
		$t10->setGst(0.14);
		$t10->setAgg(0.14);

		$t10c= $t10->getIsoCode();
		$this->rateMap[$t10c] = $t10;

		$t11 = new CustomTaxObject(self::PROVINCE_NORTHWEST_TERRITORIES, self::PROVINCE_NORTHWEST_TERRITORIES_ISO);
		$t11->setPst(0.0);
		$t11->setGst(0.05);
		$t11->setAgg(0.05);
		
		$t11c= $t11->getIsoCode();
		$this->rateMap[$t11c] = $t11;

		$t12 = new CustomTaxObject(self::PROVINCE_YUKON, self::PROVINCE_YUKON_ISO);
		$t12->setPst(0.0);
		$t12->setGst(0.05);
		$t12->setAgg(0.05);

		$t12c= $t12->getIsoCode();
		$this->rateMap[$t12c] = $t12;
	}

	private function isTaxRequestValid($tRequest){
		
		if(is_object($tRequest)){
			$state = $tRequest->getState();
			if(!empty($state)){
				return true;
			}
		}

		return false;
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

	
}

?>