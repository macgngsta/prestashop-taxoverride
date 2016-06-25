<?php
/*
*  2013
*  v1.0 - initial implementation
*  v1.1 - modified to curl to bypass 1&1 shared hosting fopen limits 
*  v1.2 - move is valid out of trequest to proxy
*
*  DISCLAIMER
*
*  Use at own risk
*
*  @author GregTam <greg@gregtam.com>
*
*  This defines a class used to obtain taxrate from the state of Washington using their provided API
*  http://dor.wa.gov/AddressRates.aspx?output=xml&addr=&city=&zip=98501
*/
require_once('TaxOverrideService.php');
require_once('CustomTaxObject.php');

//----------------------------------------
// WashingtonTaxOverrideService Class
//----------------------------------------

class WashingtonTaxOverrideService implements iTaxOverrideService{
	const HOST="http://dor.wa.gov";
	const ENDPOINT="AddressRates.aspx";

	const SUCCESS_RESULT="2";
	const INVALID_RESULT="3";
	const INVALID_REQUEST="4";

	private $host;
	private $endpoint;

	public function __construct()
	{
		$this->host=self::HOST;
		$this->endpoint=self::ENDPOINT;
	}

	public function getTaxRate($tRequest){

		//print_r($tRequest);
		//die;

		$tResponse = new TaxRateOverrideResponse();
		$url=$this->buildUrl($tRequest);
		$params = $this->buildParams($tRequest);

		if(!empty($url)){
			$url = $url.'?'.http_build_query($params, '', '&');

			PrestaShopLogger::addLog("Tax Override: wa url = ".$url, 1);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// get the result of http query
			$content = curl_exec($ch);

			curl_close($ch);

			$xml = simplexml_load_string($content);
			PrestaShopLogger::addLog("Tax Override: wa content = ".$xml, 1);

			//print_r($xml);
			//die;

			$tResponse = $this->readXml($xml);
		}
		return $tResponse;
	}

	private function isTaxRequestValid($tRequest){
		
		//seperate out the vars
		if(is_object($tRequest)){
			$state = $tRequest->getState();

			if(!empty($state)){
				$zip =$tRequest->getZip();
				$add = $tRequest->getAddress();
				$city = $tRequest->getCity();

				if(!empty($zip) && (!empty($add) && !empty($city))){
					return true;
				}
			}
		}

		PrestaShopLogger::addLog("Tax Override: invalid wa,usa tax request", 2);

		return false;
	}

	private function buildParams($tRequest){
		$params=array();

		if($this->isTaxRequestValid($tRequest)){
			$params['output']=$tRequest->getFmt();
			$params['addr']=$tRequest->getAddress();
			$params['city']=$tRequest->getCity();
			$params['zip']=$tRequest->getZip();

			PrestaShopLogger::addLog("Tax Override: querying wa,usa tax = ".$tRequest->getCity()." ".$tRequest->getZip(), 1);
		}

		return $params;
	}


	private function buildUrl($tRequest){
		$url="";

		if($this->isTaxRequestValid($tRequest)){
			$url.=$this->host;
			$url.="/";
			$url.=$this->endpoint;
		}

		return $url;
	}



	private function readXml($xml){
		$tResponse = new TaxRateOverrideResponse();

		//print_r($xml);
		//die;
		if(!empty($xml)){
			$code = $xml->attributes()->code;
			if(!empty($code)){
				switch($code){
					case self::SUCCESS_RESULT:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_SUCCESS);
						break;
					case self::INVALID_RESULT:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_NO_RESULTS);
						break;
					case self::INVALID_REQUEST:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_INVALID_REQ);
						break;
					default:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_INVALID_RESP);
				}
			}

			$tResponse->setLocationCode($xml->attributes()->loccode);
			//$xml->attributes()->localrate;
			$tResponse->setAggregateRate($xml->attributes()->rate);

			$tempRate = $xml->rate;
			if(!is_null($tempRate)){
				$tResponse->setLocationName($xml->rate->attributes()->name);
				//$xml->rate->attributes()->code;
				$tResponse->setStateRate($xml->rate->attributes()->staterate);
				$tResponse->setLocalRate($xml->rate->attributes()->localrate);
			}

			PrestaShopLogger::addLog("Tax Override: found wa,usa tax = ".$xml->attributes()->rate, 1);
		}

		return $tResponse;
	}
}

?>