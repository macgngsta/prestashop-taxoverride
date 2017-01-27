<?php

//----------------------------------------
// KeyValuePair Class
//----------------------------------------

class KeyValuePair{
	protected $_namespace = "";
	protected $_key = "";
	protected $_value = "";

	//------------------------------------
    // CONSTRUCTOR
    //------------------------------------ 
   	
   	public function __construct() 
    {
    	$this->_key = "";
    	$this->_value = "";
    	$this->_namespace = "";
    }

	//------------------------------------
    // GETTERS
    //------------------------------------ 
        
    public function getKey()
    {
    	return $this->_key;
    }
    
    //------------------------------------
        
    public function getValue()
    {
    	return $this->_value;
    }
    
    //------------------------------------
        
    public function getNamespace()
    {
    	return $this->_namespace;
    }
    
    //------------------------------------
         
 	public function isEmpty()
 	{
 		if(empty($this->_key) && empty($this->_value)){
 			return true;
 		}
 		
 		return false;
 	}

	//------------------------------------
    // SETTERS
    //------------------------------------ 
        
    public function setKey($input)
    {
    	$this->_key = $input;
    }
  
    //------------------------------------
        
    public function setValue($input)
    {
    	$this->_value = $input;
    }
    
    //------------------------------------
        
    public function setNamespace($input)
    {
    	$this->_namespace = $input;
    }

	//------------------------------------
    // OVERRIDES
    //------------------------------------ 
    	
	//@override
	public function __tostring(){ 
		return "ns: ".$this->_namespace." key: ".$this->_key." value: ".$this->_value;
	}
}

?>