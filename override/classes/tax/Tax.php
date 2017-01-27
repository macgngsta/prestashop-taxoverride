<?php
/*
*  2014
*  v1.0 - updated for prestashop 1.6
*
*  DISCLAIMER
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*  a complete override of the tax class
*/

class Tax extends TaxCore
{
	private $isCustom;
	
	//default to false
	public function __construct($isCustom = false){
		$this->isCustom=$isCustom;
	}

	public function delete()
	{
		//does nothing
		if(!$this->isCustom){
			TaxRule::deleteTaxRuleByIdTax((int)$this->id);

	        if ($this->isUsed()) {
	            return $this->historize();
	        } else {
	            return parent::delete();
	        }
		}
		else{
			return true;
		}
	}

	public function historize()
	{
		if(!$this->isCustom){
			$this->deleted = true;
        	return parent::update();
		}
		else{
			return true;
		}
	}

	public function toggleStatus()
	{
	   
		if(!$this->isCustom){
			if (parent::toggleStatus()) {
            	return $this->_onStatusChange();
	        }

	        return false;
		}
		return false;
	}

	public function update($nullValues = false)
	{
		if(!$this->isCustom){
			if (!$this->deleted && $this->isUsed()) {
	            $historized_tax = new Tax($this->id);
	            $historized_tax->historize();

	            // remove the id in order to create a new object
	            $this->id = 0;
	            $res = $this->add();

	            // change tax id in the tax rule table
	            $res &= TaxRule::swapTaxId($historized_tax->id, $this->id);
	            return $res;
	        } elseif (parent::update($null_values)) {
	            return $this->_onStatusChange();
	        }
	        return false;
		}
		return false;
	}

	protected function _onStatusChange()
	{
        if(!$this->isCustom){
        	 if (!$this->active) {
	            return TaxRule::deleteTaxRuleByIdTax($this->id);
	        }

	        return true;
	    }
        return true;
	}

	public function isUsed()
	{
		if(!$this->isCustom){
        	return Db::getInstance()->getValue('
			SELECT `id_tax`
			FROM `'._DB_PREFIX_.'order_detail_tax`
			WHERE `id_tax` = '.(int)$this->id
	        );
        }
        else{
        	return false;
        }
	}

    /**
     * Returns the product tax
     *
     * @param int $id_product
     * @param int $id_country
     * @return Tax
     */
    public static function getProductTaxRate($id_product, $id_address = null, Context $context = null)
    {
    	if(!$this->isCustom){
	        if ($context == null) {
	            $context = Context::getContext();
	        }

	        $address = Address::initialize($id_address);
	        $id_tax_rules = (int)Product::getIdTaxRulesGroupByIdProduct($id_product, $context);

	        $tax_manager = TaxManagerFactory::getManager($address, $id_tax_rules);
	        $tax_calculator = $tax_manager->getTaxCalculator();

	        return $tax_calculator->getTotalRate();
    	}
    	else{
    		return $this->rate;
    	}
    }
}

