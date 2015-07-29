<?php
/*
*  2014
*  v1.0 - updated for prestashop 1.6
*
*  DISCLAIMER
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  Override existing TaxCalculatorCore in order not to use the Tax object - we dont need all the db helpers
*/

class TaxCalculator extends TaxCalculatorCore
{
	public function __construct(array $taxes = array(), $computation_method = TaxCalculator::COMBINE_METHOD)
	{
		$validTaxes = array();

		if(!empty($taxes)){
			// sanity check
			foreach ($taxes as $tax){
				if (!is_null($tax)){
					$validTaxes[]=$tax;
				}
			}
		}

		$this->taxes = $validTaxes;
		$this->computation_method = (int)$computation_method;
	}
}

