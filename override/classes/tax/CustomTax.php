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
*/

class CustomTax extends TaxCore extends ObjectModel
{
	public function delete()
	{
		//does nothing
	}

	public function historize()
	{
		//does nothing
		return true;
	}

	public function toggleStatus()
	{
	   //does nothing
		return true;
	}

	public function update($nullValues = false)
	{
		//does nothing
		return true;
	}

	protected function _onStatusChange()
	{
        //does nothing
		return true;
	}

	public function isUsed()
	{
		//does nothing
		return false;
	}
}

