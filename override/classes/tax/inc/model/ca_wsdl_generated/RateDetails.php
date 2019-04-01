<?php

class RateDetails
{

    /**
     * @var string $CalcMethod
     */
    protected $CalcMethod = null;

    /**
     * @var anyType $City
     */
    protected $City = null;

    /**
     * @var string $Comments
     */
    protected $Comments = null;

    /**
     * @var string $Confidence
     */
    protected $Confidence = null;

    /**
     * @var string $County
     */
    protected $County = null;

    /**
     * @var string $Jurisdiction
     */
    protected $Jurisdiction = null;

    /**
     * @var string $TAC
     */
    protected $TAC = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getCalcMethod()
    {
      return $this->CalcMethod;
    }

    /**
     * @param string $CalcMethod
     * @return RateDetails
     */
    public function setCalcMethod($CalcMethod)
    {
      $this->CalcMethod = $CalcMethod;
      return $this;
    }

    /**
     * @return anyType
     */
    public function getCity()
    {
      return $this->City;
    }

    /**
     * @param anyType $City
     * @return RateDetails
     */
    public function setCity($City)
    {
      $this->City = $City;
      return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
      return $this->Comments;
    }

    /**
     * @param string $Comments
     * @return RateDetails
     */
    public function setComments($Comments)
    {
      $this->Comments = $Comments;
      return $this;
    }

    /**
     * @return string
     */
    public function getConfidence()
    {
      return $this->Confidence;
    }

    /**
     * @param string $Confidence
     * @return RateDetails
     */
    public function setConfidence($Confidence)
    {
      $this->Confidence = $Confidence;
      return $this;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
      return $this->County;
    }

    /**
     * @param string $County
     * @return RateDetails
     */
    public function setCounty($County)
    {
      $this->County = $County;
      return $this;
    }

    /**
     * @return string
     */
    public function getJurisdiction()
    {
      return $this->Jurisdiction;
    }

    /**
     * @param string $Jurisdiction
     * @return RateDetails
     */
    public function setJurisdiction($Jurisdiction)
    {
      $this->Jurisdiction = $Jurisdiction;
      return $this;
    }

    /**
     * @return string
     */
    public function getTAC()
    {
      return $this->TAC;
    }

    /**
     * @param string $TAC
     * @return RateDetails
     */
    public function setTAC($TAC)
    {
      $this->TAC = $TAC;
      return $this;
    }

}
