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
		// sanity check
		foreach ($taxes as $tax)
			if (!($tax instanceof CustomTax) || !($tax instanceof Tax))
				throw new Exception('Invalid Custom Tax Object');

		$this->taxes = $taxes;
		$this->computation_method = (int)$computation_method;
	}
}

