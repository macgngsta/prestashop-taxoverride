<?php

class ArrayOfRateInformation implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var RateInformation[] $RateInformation
     */
    protected $RateInformation = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return RateInformation[]
     */
    public function getRateInformation()
    {
      return $this->RateInformation;
    }

    /**
     * @param RateInformation[] $RateInformation
     * @return ArrayOfRateInformation
     */
    public function setRateInformation(array $RateInformation = null)
    {
      $this->RateInformation = $RateInformation;
      return $this;
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset An offset to check for
     * @return boolean true on success or false on failure
     */
    public function offsetExists($offset)
    {
      return isset($this->RateInformation[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return RateInformation
     */
    public function offsetGet($offset)
    {
      return $this->RateInformation[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param RateInformation $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->RateInformation[] = $value;
      } else {
        $this->RateInformation[$offset] = $value;
      }
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to unset
     * @return void
     */
    public function offsetUnset($offset)
    {
      unset($this->RateInformation[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return RateInformation Return the current element
     */
    public function current()
    {
      return current($this->RateInformation);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->RateInformation);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->RateInformation);
    }

    /**
     * Iterator implementation
     *
     * @return boolean Return the validity of the current position
     */
    public function valid()
    {
      return $this->key() !== null;
    }

    /**
     * Iterator implementation
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
      reset($this->RateInformation);
    }

    /**
     * Countable implementation
     *
     * @return RateInformation Return count of elements
     */
    public function count()
    {
      return count($this->RateInformation);
    }

}
