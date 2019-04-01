<?php

class CATaxRateAPI extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
      'Hello' => '\\Hello',
      'HelloResponse' => '\\HelloResponse',
      'GetRate' => '\\GetRate',
      'CARateRequest' => '\\CARateRequest',
      'GetRateResponse' => '\\GetRateResponse',
      'CARateResponseCollection' => '\\CARateResponseCollection',
      'ArrayOfCARateResponse' => '\\ArrayOfCARateResponse',
      'CARateResponse' => '\\CARateResponse',
      'ArrayOfError' => '\\ArrayOfError',
      'Error' => '\\ErrorCustom',
      'ArrayOfRateInformation' => '\\ArrayOfRateInformation',
      'RateInformation' => '\\RateInformation',
      'RateDetails' => '\\RateDetails',
    );

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct(array $options = array(), $wsdl = null)
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      $options = array_merge(array (
      'features' => 1,
    ), $options);
      if (!$wsdl) {
        $wsdl = 'http://services.gis.boe.ca.gov/api/taxrates/rates.svc?singlewsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param Hello $parameters
     * @return HelloResponse
     */
    public function Hello(Hello $parameters)
    {
      return $this->__soapCall('Hello', array($parameters));
    }

    /**
     * @param GetRate $parameters
     * @return GetRateResponse
     */
    public function GetRate(GetRate $parameters)
    {
      return $this->__soapCall('GetRate', array($parameters));
    }

}
