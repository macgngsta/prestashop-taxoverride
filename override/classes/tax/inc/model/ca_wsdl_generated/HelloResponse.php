<?php

class HelloResponse
{

    /**
     * @var string $HelloResult
     */
    protected $HelloResult = null;

    /**
     * @param string $HelloResult
     */
    public function __construct($HelloResult)
    {
      $this->HelloResult = $HelloResult;
    }

    /**
     * @return string
     */
    public function getHelloResult()
    {
      return $this->HelloResult;
    }

    /**
     * @param string $HelloResult
     * @return HelloResponse
     */
    public function setHelloResult($HelloResult)
    {
      $this->HelloResult = $HelloResult;
      return $this;
    }

}
