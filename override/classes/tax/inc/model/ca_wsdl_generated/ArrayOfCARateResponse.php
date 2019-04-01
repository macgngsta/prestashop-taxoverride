<?php

class ArrayOfCARateResponse implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var CARateResponse[] $CARateResponse
     */
    protected $CARateResponse = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return CARateResponse[]
     */
    public function getCARateResponse()
    {
      return $this->CARateResponse;
    }

    /**
     * @param CARateResponse[] $CARateResponse
     * @return ArrayOfCARateResponse
     */
    public function setCARateResponse(array $CARateResponse = null)
    {
      $this->CARateResponse = $CARateResponse;
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
      return isset($this->CARateResponse[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return CARateResponse
     */
    public function offsetGet($offset)
    {
      return $this->CARateResponse[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param CARateResponse $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->CARateResponse[] = $value;
      } else {
        $this->CARateResponse[$offset] = $value;
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
      unset($this->CARateResponse[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return CARateResponse Return the current element
     */
    public function current()
    {
      return current($this->CARateResponse);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->CARateResponse);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->CARateResponse);
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
      reset($this->CARateResponse);
    }

    /**
     * Countable implementation
     *
     * @return CARateResponse Return count of elements
     */
    public function count()
    {
      return count($this->CARateResponse);
    }

}
