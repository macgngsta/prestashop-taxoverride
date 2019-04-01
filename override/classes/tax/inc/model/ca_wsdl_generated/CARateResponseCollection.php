<?php

class CARateResponseCollection
{

    /**
     * @var string $AppVersion
     */
    protected $AppVersion = null;

    /**
     * @var ArrayOfCARateResponse $CARateResponses
     */
    protected $CARateResponses = null;

    /**
     * @var string $Disclaimer
     */
    protected $Disclaimer = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getAppVersion()
    {
      return $this->AppVersion;
    }

    /**
     * @param string $AppVersion
     * @return CARateResponseCollection
     */
    public function setAppVersion($AppVersion)
    {
      $this->AppVersion = $AppVersion;
      return $this;
    }

    /**
     * @return ArrayOfCARateResponse
     */
    public function getCARateResponses()
    {
      return $this->CARateResponses;
    }

    /**
     * @param ArrayOfCARateResponse $CARateResponses
     * @return CARateResponseCollection
     */
    public function setCARateResponses($CARateResponses)
    {
      $this->CARateResponses = $CARateResponses;
      return $this;
    }

    /**
     * @return string
     */
    public function getDisclaimer()
    {
      return $this->Disclaimer;
    }

    /**
     * @param string $Disclaimer
     * @return CARateResponseCollection
     */
    public function setDisclaimer($Disclaimer)
    {
      $this->Disclaimer = $Disclaimer;
      return $this;
    }

}
