<?php

class GetRateResponse
{

    /**
     * @var CARateResponseCollection $GetRateResult
     */
    protected $GetRateResult = null;

    /**
     * @param CARateResponseCollection $GetRateResult
     */
    public function __construct($GetRateResult)
    {
      $this->GetRateResult = $GetRateResult;
    }

    /**
     * @return CARateResponseCollection
     */
    public function getGetRateResult()
    {
      return $this->GetRateResult;
    }

    /**
     * @param CARateResponseCollection $GetRateResult
     * @return GetRateResponse
     */
    public function setGetRateResult($GetRateResult)
    {
      $this->GetRateResult = $GetRateResult;
      return $this;
    }

}
