<?php

class GetRate
{

    /**
     * @var CARateRequest $request
     */
    protected $request = null;

    /**
     * @param CARateRequest $request
     */
    public function __construct($request)
    {
      $this->request = $request;
    }

    /**
     * @return CARateRequest
     */
    public function getRequest()
    {
      return $this->request;
    }

    /**
     * @param CARateRequest $request
     * @return GetRate
     */
    public function setRequest($request)
    {
      $this->request = $request;
      return $this;
    }

}
