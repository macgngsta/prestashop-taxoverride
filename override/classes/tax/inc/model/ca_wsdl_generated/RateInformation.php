<?php

class RateInformation
{

    /**
     * @var RateDetails $Details
     */
    protected $Details = null;

    /**
     * @var float $Rate
     */
    protected $Rate = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return RateDetails
     */
    public function getDetails()
    {
      return $this->Details;
    }

    /**
     * @param RateDetails $Details
     * @return RateInformation
     */
    public function setDetails($Details)
    {
      $this->Details = $Details;
      return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
      return $this->Rate;
    }

    /**
     * @param float $Rate
     * @return RateInformation
     */
    public function setRate($Rate)
    {
      $this->Rate = $Rate;
      return $this;
    }

}
