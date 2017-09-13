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
require_once('model/CustomTaxObject.php');
require_once('util/CurlHelper.php');

//----------------------------------------
// WashingtonTaxOverrideService Class
//----------------------------------------

class WashingtonTaxOverrideService implements iTaxOverrideService{
	const HOST="http://webgis.dor.wa.gov";
	const ENDPOINT="webapi/addressrates.aspx";

	const ENDPOINT_SUCCESS_RESULT="0";
	const ENDPOINT_SUCCESS_RESULT2="2";
	const ENDPOINT_INVALID_RESULT="3";
	const ENDPOINT_INVALID_REQUEST="4";

	const CURL_STATUS_UNKNOWN_ERROR=-1;
	const CURL_STATUS_SUCCESS=1;
	const CURL_STATUS_CLIENT_ERROR=2;
	const CURL_STATUS_SERVER_ERROR=3;

	const ENABLE_CURL=false;

	private $host;
	private $endpoint;
	private $logId;

	//----------------------------------------

	public function __construct($logId)
	{
		$this->host=self::HOST;
		$this->endpoint=self::ENDPOINT;
		$this->logId=$logId;
	}

	//----------------------------------------

	public function getTaxRate($tRequest){
		if(self::ENABLE_CURL){
			return $this->getTaxRateWithCurl($tRequest);
		}
		else{
			return $this->getTaxRateWithSimple($tRequest);
		}
	}

	//----------------------------------------

	private function getTaxRateWithSimple($tRequest){
		$tResponse=null;

		PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > selected Simple", 1);

		if(self::isTaxRequestValid($tRequest)){
			$tResponse = new TaxRateOverrideResponse();
			$url=$this->buildUrl($tRequest);
			$params = $this->buildParams($tRequest);

			if(!empty($url)){
				$url = $url.'?'.http_build_query($params, '', '&');

				try{
					$content = file_get_contents($url);
					if($content === false){
						PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > simple content empty", 1);
					}
					else{
						$xml = simplexml_load_string($content);
						$tResponse = $this->readXml($xml);
					}
				}
				catch(Exception $e){
					PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > simple exception = ".$e, 1);
				}
			}
		}		

		return $tResponse;
	}

	//----------------------------------------

	private function getTaxRateWithCurl($tRequest){
		$tResponse=null;
		
		PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > selected Curl", 1);

		if(self::isTaxRequestValid($tRequest)){
			$tResponse = new TaxRateOverrideResponse();
			$url=$this->buildUrl($tRequest);
			$params = $this->buildParams($tRequest);

			if(!empty($url)){
				$tparams = http_build_query($params, '', '&');
				$toCurl = new CurlHelper ($url, $tparams, null, false);
				$toCurl->execute();

				switch($sCode)
				{
					case self::CURL_STATUS_SUCCESS:
						PrestaShopLogger::addLog("WashingtonTaxOverrideService ".$this->logId." > wa content = ".$content, 1);
						$xml = simplexml_load_string($content);
						$tResponse = $this->readXml($xml);
						break;
					case self::CURL_STATUS_CLIENT_ERROR:
						PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > curl request was invalid", 1);
						break;
					case self::CURL_STATUS_SERVER_ERROR:
						PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > curl server encountered an error: ".$toCurl->getError(), 1);
						break;
					case self::CURL_STATUS_UNKNOWN_ERROR:
					default:
						PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > curl something awful happened: ".$toCurl->getError(), 1);
				}
			}
		}
		return $tResponse;
	}

	//----------------------------------------

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

		PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > invalid wa,usa tax request", 1);

		return false;
	}

	//----------------------------------------

	private function buildParams($tRequest){
		$params=array();

		if($this->isTaxRequestValid($tRequest)){
			$params['output']=$tRequest->getFmt();
			$params['addr']=$tRequest->getAddress();
			$params['city']=$tRequest->getCity();
			$params['zip']=$tRequest->getZip();

			PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > querying wa,usa tax = ".$tRequest->getAddress()." ".$tRequest->getCity()." ".$tRequest->getZip(), 1);
		}

		return $params;
	}

	//----------------------------------------

	private function buildUrl($tRequest){
		$url="";

		if($this->isTaxRequestValid($tRequest)){
			$url.=$this->host;
			$url.="/";
			$url.=$this->endpoint;
		}

		return $url;
	}

	//----------------------------------------

	private function readXml($xml){
		$tResponse = new TaxRateOverrideResponse();

		//print_r($xml);
		//die;
		if(!empty($xml)){
			$code = $xml->attributes()->code;
			if(!empty($code)){
				switch($code){
					case self::ENDPOINT_SUCCESS_RESULT:
					case self::ENDPOINT_SUCCESS_RESULT2:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_SUCCESS);
						break;
					case self::ENDPOINT_INVALID_RESULT:
						$tResponse->setStatus(TaxRateOverrideResponse::STATUS_NO_RESULTS);
						break;
					case self::ENDPOINT_INVALID_REQUEST:
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

			PrestaShopLogger::addLog("WashingtonTaxOverrideService: ".$this->logId." > found wa,usa tax = ".$xml->attributes()->rate, 1);
		}

		return $tResponse;
	}

	//----------------------------------------
}

?>