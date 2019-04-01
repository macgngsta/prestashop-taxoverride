<?php

class CARateResponse
{

    /**
     * @var int $BufferSize
     */
    protected $BufferSize = null;

    /**
     * @var CARateRequest $CARateRequest
     */
    protected $CARateRequest = null;

    /**
     * @var ArrayOfError $Errors
     */
    protected $Errors = null;

    /**
     * @var \DateTime $ResponseDate
     */
    protected $ResponseDate = null;

    /**
     * @var ArrayOfRateInformation $Responses
     */
    protected $Responses = null;

    /**
     * @var string $TermsOfUse
     */
    protected $TermsOfUse = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return int
     */
    public function getBufferSize()
    {
      return $this->BufferSize;
    }

    /**
     * @param int $BufferSize
     * @return CARateResponse
     */
    public function setBufferSize($BufferSize)
    {
      $this->BufferSize = $BufferSize;
      return $this;
    }

    /**
     * @return CARateRequest
     */
    public function getCARateRequest()
    {
      return $this->CARateRequest;
    }

    /**
     * @param CARateRequest $CARateRequest
     * @return CARateResponse
     */
    public function setCARateRequest($CARateRequest)
    {
      $this->CARateRequest = $CARateRequest;
      return $this;
    }

    /**
     * @return ArrayOfError
     */
    public function getErrors()
    {
      return $this->Errors;
    }

    /**
     * @param ArrayOfError $Errors
     * @return CARateResponse
     */
    public function setErrors($Errors)
    {
      $this->Errors = $Errors;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getResponseDate()
    {
      if ($this->ResponseDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->ResponseDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $ResponseDate
     * @return CARateResponse
     */
    public function setResponseDate(\DateTime $ResponseDate = null)
    {
      if ($ResponseDate == null) {
       $this->ResponseDate = null;
      } else {
        $this->ResponseDate = $ResponseDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return ArrayOfRateInformation
     */
    public function getResponses()
    {
      return $this->Responses;
    }

    /**
     * @param ArrayOfRateInformation $Responses
     * @return CARateResponse
     */
    public function setResponses($Responses)
    {
      $this->Responses = $Responses;
      return $this;
    }

    /**
     * @return string
     */
    public function getTermsOfUse()
    {
      return $this->TermsOfUse;
    }

    /**
     * @param string $TermsOfUse
     * @return CARateResponse
     */
    public function setTermsOfUse($TermsOfUse)
    {
      $this->TermsOfUse = $TermsOfUse;
      return $this;
    }

}
