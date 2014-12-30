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

		$tResponse = new TaxRateOverrideResponse();
		$url=$this->buildUrl($tRequest);

		//echo $url;

		if(!empty($url)){

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// get the result of http query
			$content = curl_exec($ch);
			curl_close($ch);

			$xml = simplexml_load_string($content);

			//print_r($xml);

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

				if(!empty($zip) || (!empty($add) && !empty($city))){
					return true;
				}
			}
		}

		return false;
	}

	private function buildUrl($tRequest){
		$url="";

		if($this->isTaxRequestValid($tRequest)){
			$url.=$this->host;
			$url.="/";
			$url.=$this->endpoint;
			$url.="?";
			$url.="output=";
			$url.=$tRequest->getFmt();
			$url.="&";
			$url.="addr=";
			$url.=$tRequest->getAddress();
			$url.="&";
			$url.="city=";
			$url.=$tRequest->getCity();
			$url.="&";
			$url.="zip=";
			$url.=$tRequest->getZip();
		}

		return $url;
	}



	private function readXml($xml){
		$tResponse = new TaxRateOverrideResponse();

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

			$tResponse->setLocationName($xml->rate->attributes()->name);
			//$xml->rate->attributes()->code;
			$tResponse->setStateRate($xml->rate->attributes()->staterate);
			$tResponse->setLocalRate($xml->rate->attributes()->localrate);

		}

		return $tResponse;
	}
}

?>