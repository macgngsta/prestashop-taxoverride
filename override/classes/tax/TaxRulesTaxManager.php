<?php
/*
*  2014
*  v1.0 - use interface
*  v1.1 - updated for prestashop 1.6
*  v1.2 - added logging, added enablement
*
*  DISCLAIMER
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  Override existing taxrulestaxmanager to add in some override tax rules
*/

//this should already be set:
// https://my.1and1.com/ExtPhpIni?__lf=HomeFlow

ini_set('allow_url_fopen','on');

require_once('CustomTax.php');
require_once('inc/ITaxOverrideService.php');
require_once('inc/WashingtonTaxOverrideService.php');
require_once('inc/CanadaTaxOverrideService.php');
require_once('inc/CaliforniaTaxOverrideService.php');
require_once('inc/GeorgiaTaxOverrideService.php');

class TaxRulesTaxManager extends TaxRulesTaxManagerCore implements TaxManagerInterface
{
	// use admin > localization > countries
	// by default UNITED STATES = 21, CANADA = 4
	// for states CALIFORNIA= 5, WASHINGTON= 47
	const LOCALIZATION_ID_COUNTRY_UNITED_STATES = 21;
	const LOCALIZATION_ID_COUNTRY_CANADA = 4;
	const LOCALIZATION_ID_STATE_CALIFORNIA = 5;
	const LOCALIZATION_ID_STATE_GEORGIA= 10;
	const LOCALIZATION_ID_STATE_WASHINGTON = 47;
	const LOCALIZATION_ID_INVALID = -1;

	//ENABLE THE STATES HERE
	const ENABLE_WASHINGTON = true;
	const ENABLE_CANADA = true;
	const ENABLE_CALIFORNIA = true;
	const ENABLE_GEORGIA = true;

	public $address;
	public $type;
	public $tax_calculator;

	/**
	 * 
	 * @param Address $address
	 * @param mixed An additional parameter for the tax manager (ex: tax rules id for TaxRuleTaxManager)
	 */
	public function __construct(Address $address, $type)
	{
		$this->address = $address;
		$this->type = $type;
		$this->taxOverrideService=null;
	}

	/**
	* Returns true if this tax manager is available for this address
	*
	* @return boolean
	*/
	public static function isAvailableForThisAddress(Address $address)
	{
		return true; // default manager + override manager, available for all addresses
	}

	/**
	*
	* !!!!!! ENTRY METHOD !!!!!!
	* 
	* Return the tax calculator associated to this address
	*
	* @return TaxCalculator
	*/
	public function getTaxCalculator()
	{
		static $tax_enabled = null;

		if (isset($this->tax_calculator))
			return $this->tax_calculator;

		if ($tax_enabled === null)
			$tax_enabled = Configuration::get('PS_TAX');
		
		if (!$tax_enabled)
			return new TaxCalculator(array());
	
		$taxes = array();
		$postcode = 0;
		if (!empty($this->address->postcode))
			$postcode = $this->address->postcode;

		//use this to track the session
		$logId=round(microtime(true) * 1000);
		$customCalculator = $this->getOverrideCalculator($logId, $this->address);

		//check to see if there are any overrides
		if(!is_null($customCalculator)){
			PrestaShopLogger::addLog("TaxRulesTaxManager: ".$logId." > custom calculator", 1);
			return $customCalculator;
		}
		else{
			PrestaShopLogger::addLog("TaxRulesTaxManager: ".$logId." > default calculator", 1);
			//use default tax calculator
			return $this->getDefaultTaxCalculator();
		}
	}

	/*------------------------------------------------------*
	 * OVERRIDE BEHAVIOR
	 * must calculate these taxes automatically
	 *------------------------------------------------------*/

	private function getOverrideCalculator($logId, $address){

		$tOverride = null;
		$tOverrideRequest = new TaxRateOverrideRequest();

		$state="";

		//get the state name
		try{
			$state = State::getNameById($address->id_state);
			$tOverrideRequest->setState($state);
		} catch (Exception $e) {
		    PrestaShopLogger::addLog("TaxRulesTaxManager: ".$logId." > exception = ".$e->getMessage(), 1);
		} 

		$country_id = self::LOCALIZATION_ID_INVALID;
		if(!empty($address->id_country)){
			$country_id = $address->id_country;
		}

		if($country_id != self::LOCALIZATION_ID_INVALID){
			if(self::ENABLE_CANADA && $country_id==self::LOCALIZATION_ID_COUNTRY_CANADA){
				//use canada
				$tOverride = new CanadaTaxOverrideService($logId);
			}
			else if($country_id==self::LOCALIZATION_ID_COUNTRY_UNITED_STATES){
				$state_id = self::LOCALIZATION_ID_INVALID;
				if(!empty($address->id_state)){
					$state_id=$address->id_state;
				}

				if($state_id!=self::LOCALIZATION_ID_INVALID){
					if(self::ENABLE_WASHINGTON && $state_id==self::LOCALIZATION_ID_STATE_WASHINGTON){
						//use washington
						$tOverride = new WashingtonTaxOverrideService($logId);
						
						//set zip
						$tOverrideRequest->setZip($address->postcode);

		        		//get city
		        		$tOverrideRequest->setCity($address->city);

		        		//get address
		        		$tOverrideRequest->setAddress($address->address1);
					}
				}
			}
		}

		//PrestaShopLogger::addLog("Tax Rules Tax Manager = ".$tOverride, 1);

		if($tOverride != null && !isEmpty($tOverride)){
			$tOverrideResponse = $tOverride->getTaxRate($tOverrideRequest);
			if($tOverrideResponse!=null && $tOverrideResponse->isValid())
			{
				if($tOverrideResponse->getStatus() == TaxRateOverrideResponse::STATUS_SUCCESS){
					$taxes = array();

					//create state
					$stateTax = new CustomTax();
					$stateTax->name=$tOverrideRequest->getState()."_tax";
					$stateTax->rate=$tOverrideResponse->getStateRate()*100;
					$stateTax->active=true;

					//create local
					$localTax = new CustomTax();
					$localTax->name=$tOverrideResponse->getLocationName()."_tax";
					$localTax->rate=$tOverrideResponse->getLocalRate()*100;
					$localTax->active=true;

					$taxes[]=$stateTax;
					$taxes[]=$localTax;

					$customTaxCalc = new TaxCalculator($taxes, TaxCalculator::COMBINE_METHOD);

					return $customTaxCalc;
				}
				else{
					PrestaShopLogger::addLog("TaxRulesTaxManager: ".$logId." > status not successful", 1);
				}
			}
		}

		return null;
	}

	/*------------------------------------------------------*
	 * DEFAULT BEHAVIOR
	 *------------------------------------------------------*/

    /**
    * Return the tax calculator associated to this address
    *
    * @return TaxCalculator
    */

    public function getDefaultTaxCalculator()
    {
        static $tax_enabled = null;

        if (isset($this->tax_calculator)) {
            return $this->tax_calculator;
        }

        if ($tax_enabled === null) {
            $tax_enabled = $this->configurationManager->get('PS_TAX');
        }

        if (!$tax_enabled) {
            return new TaxCalculator(array());
        }

        $taxes = array();
        $postcode = 0;

        if (!empty($this->address->postcode)) {
            $postcode = $this->address->postcode;
        }

        $cache_id = (int)$this->address->id_country.'-'.(int)$this->address->id_state.'-'.$postcode.'-'.(int)$this->type;

        if (!Cache::isStored($cache_id)) {
            $rows = Db::getInstance()->executeS('
				SELECT tr.*
				FROM `'._DB_PREFIX_.'tax_rule` tr
				JOIN `'._DB_PREFIX_.'tax_rules_group` trg ON (tr.`id_tax_rules_group` = trg.`id_tax_rules_group`)
				WHERE trg.`active` = 1
				AND tr.`id_country` = '.(int)$this->address->id_country.'
				AND tr.`id_tax_rules_group` = '.(int)$this->type.'
				AND tr.`id_state` IN (0, '.(int)$this->address->id_state.')
				AND (\''.pSQL($postcode).'\' BETWEEN tr.`zipcode_from` AND tr.`zipcode_to`
					OR (tr.`zipcode_to` = 0 AND tr.`zipcode_from` IN(0, \''.pSQL($postcode).'\')))
				ORDER BY tr.`zipcode_from` DESC, tr.`zipcode_to` DESC, tr.`id_state` DESC, tr.`id_country` DESC');

            $behavior = 0;
            $first_row = true;

            foreach ($rows as $row) {
                $tax = new Tax((int)$row['id_tax']);

                $taxes[] = $tax;

                // the applied behavior correspond to the most specific rules
                if ($first_row) {
                    $behavior = $row['behavior'];
                    $first_row = false;
                }

                if ($row['behavior'] == 0) {
                    break;
                }
            }
            $result = new TaxCalculator($taxes, $behavior);
            Cache::store($cache_id, $result);
            return $result;
        }

        return Cache::retrieve($cache_id);
    }
}
