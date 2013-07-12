<?php
/*
*  2013
*  v1.0 - initial implementation
*  v1.1 - added canada tax - need to implement using iso_code
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  Modify the taxrulesgroup to pull washington tax directly
*/

ini_set('allow_url_fopen','on');

include('inc/TaxOverrideService.php');
include('inc/WashingtonTaxOverrideService.php');
include('inc/CanadaTaxOverrideService.php');

class TaxRulesGroup extends TaxRulesGroupCore
{

	/* NEW only if there is an address */

	public static function getTaxes($id_tax_rules_group, $id_country, $id_state, $id_county)
	{	
		//get the cart
		global $cart;

	    if (empty($id_tax_rules_group) OR empty($id_country))
	        return array(new Tax()); // No Tax

       if (isset(self::$_taxes[$id_tax_rules_group.'-'.$id_country.'-'.$id_state.'-'.$id_county]))
            return self::$_taxes[$id_tax_rules_group.'-'.$id_country.'-'.$id_state.'-'.$id_county];

		$order = 'DESC';

		$country = new Country((int)$id_country);
		$state = new State((int)$id_state);

		//var_dump($state);

		//add an override
		$tOverride = new DefaultTaxOverrideService();
		$tOverrideRequest = new TaxRateOverrideRequest();
		$tOverrideResponse = new TaxRateOverrideResponse();

		if($country->iso_code == "US"){
			//set state
			$tOverrideRequest->setState($state->iso_code);

			if($state->iso_code == "WA"){

				$tOverride = new WashingtonTaxOverrideService();

	 			if (is_object($cart)){
	        		$id_address = $cart->id_address_delivery;
	        		$address_infos = Address::getCountryAndState($id_address);

	        		//get zip
	        		$zip = $address_infos['postcode'];
	        		$tOverrideRequest->setZip($zip);

	        		//execute override
	        		$tOverrideResponse = $tOverride->getTaxRate($tOverrideRequest);
	        	}
			}
		}
		else if($country->iso_code == "CA"){
			$tOverrideRequest->setState($state->iso_code);

			$tOverride = new CanadaTaxOverrideService();
			$tOverrideResponse = $tOverride->getTaxRate($tOverrideRequest);

			//echo $tOverrideResponse;
		}

		if($tOverrideResponse->isValid())
		{
			if($tOverrideResponse->getStatus() == TaxRateOverrideResponse::STATUS_SUCCESS){
				$taxes = array();

				//create state
				$stateTax = new Tax();
				$stateTax->name=$tOverrideRequest->getState()." tax";
				$stateTax->rate=$tOverrideResponse->getStateRate()*100;
				$stateTax->active=true;

				//create local
				$localTax = new Tax();
				$localTax->name=$tOverrideResponse->getLocationName()." tax";
				$localTax->rate=$tOverrideResponse->getLocalRate()*100;
				$localTax->active=true;

				$taxes[]=$stateTax;
				$taxes[]=$localTax;

				return $taxes;
			}
		}

		/* Canada (Country then State) */
		if (Country::getIsoById((int)$id_country) == self::$canada_iso && in_array($state->iso_code, self::$canada_states_iso))
			$order = 'ASC';

	    $rows = Db::getInstance()->ExecuteS('
	    SELECT *
	    FROM `'._DB_PREFIX_.'tax_rule`
	    WHERE `id_country` = '.(int)$id_country.'
	    AND `id_tax_rules_group` = '.(int)$id_tax_rules_group.'
	    AND `id_state` IN (0, '.(int)$id_state.')
	    AND `id_county` IN (0, '.(int)$id_county.')
	    ORDER BY `id_county` '.$order.', `id_state` '.$order);

	    $taxes = array();
	    foreach ($rows AS $row)
	    {
          if ($row['id_county'] != 0)
          {
          	switch($row['county_behavior'])
          	{
          		case County::USE_BOTH_TAX:
                 $taxes[] = new Tax($row['id_tax']);
          		break;

          		case County::USE_COUNTY_TAX:
                  $taxes = array(new Tax($row['id_tax']));
          		break 2;

          		case County::USE_STATE_TAX: // do nothing
          		break;
          	}
          }
	       elseif ($row['id_state'] != 0)
	       {
	            switch($row['state_behavior'])
	            {
	                case PS_STATE_TAX: // use only product tax
                        $taxes[] = new Tax($row['id_tax']);
    	                break 2; // switch + foreach

    	            case PS_BOTH_TAX:
    	                $taxes[] = new Tax($row['id_tax']);
    	                break;

	                case PS_PRODUCT_TAX: // do nothing use country tax
	                    break;
	            }
	       }
	       else
	            $taxes[] = new Tax((int)$row['id_tax']);
	    }

	    self::$_taxes[$id_tax_rules_group.'-'.$id_country.'-'.$id_state.'-'.$id_county] = $taxes;

	    //var_dump($taxes);

       return $taxes;
	}
}

