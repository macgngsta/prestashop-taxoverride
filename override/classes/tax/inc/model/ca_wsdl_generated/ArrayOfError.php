<?php

class ArrayOfError implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var Error[] $Error
     */
    protected $Error = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return Error[]
     */
    public function getError()
    {
      return $this->Error;
    }

    /**
     * @param Error[] $Error
     * @return ArrayOfError
     */
    public function setError(array $Error = null)
    {
      $this->Error = $Error;
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
      return isset($this->Error[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return Error
     */
    public function offsetGet($offset)
    {
      return $this->Error[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param Error $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->Error[] = $value;
      } else {
        $this->Error[$offset] = $value;
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
      unset($this->Error[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return Error Return the current element
     */
    public function current()
    {
      return current($this->Error);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->Error);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->Error);
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
      reset($this->Error);
    }

    /**
     * Countable implementation
     *
     * @return Error Return count of elements
     */
    public function count()
    {
      return count($this->Error);
    }

}
