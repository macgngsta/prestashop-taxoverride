<?php


//----------------------------------------
// CurlHelper Class
//----------------------------------------

//need to be able to post
//url
//set some cookie headers
class CurlHelper {
	protected $_url = "";
	//can use url encoded parameter string
	protected $_queryParams = "";
	protected $_cookieOptions = "";
	
	protected $_result = "";
	protected $_status = "";
    protected $_error = "";
	
	protected $_executed = false;
	protected $_isPost = false;
	
    //------------------------------------
    // CONSTRUCTOR
    //------------------------------------ 
    
	public function __construct($url, $qParams, $cookieOptions, $isPost) 
    { 
         $this->_url = $url; 
         $this->_queryParams = $qParams;
         $this->_cookieOptions = $cookieOptions;
         
         //init these
         $this->_result = "";
         $this->_status = "";
         $this->_error ="";
         
         $this->_executed = false;
         $this->_isPost = $isPost;
    } 
    
    //------------------------------------
        
    public function getResponse()
    {
    	if($this->_executed)
    	{
    		return $this->_result;
    	}
    	else
    	{
    		throw new Exception('curl never executed');
    	}
    }

    public function getError()
    {
        if($this->_executed)
        {
            return $this->_error;
        }
        else
        {
            throw new Exception('curl never executed');
        }
    }
    
    //------------------------------------
    
    public function getHttpStatus()
    {
    	if($this->_executed)
    	{
    		return $this->_status;
    	}
    	else
    	{
    		throw new Exception('curl never executed');
    	}
    }
    
    //------------------------------------
    
    public function execute() 
    { 
        if(!empty($this->_url))
        {
        	$s = curl_init(); 
			
			$aggUrl = $this->_url;
			
			//setup some default timeouts
			//time the connection should be open
			curl_setopt($s,CURLOPT_CONNECTTIMEOUT,3);
			//max time to execute
			curl_setopt($s,CURLOPT_TIMEOUT,5);
			curl_setopt($s, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($s, CURLOPT_SSLVERSION, 3);
            curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($s,CURLOPT_VERBOSE, true);
			
			//include posts
			if(!empty($this->_queryParams))
			{
				if($this->_isPost)
				{
					curl_setopt($s,CURLOPT_POST,true); 
            		curl_setopt($s,CURLOPT_POSTFIELDS,$this->_queryParams);
				}
				else
				{
					$aggUrl = $aggUrl."?".$this->_queryParams;
				}
			}
			
			curl_setopt($s,CURLOPT_URL, $aggUrl);
			
			//include some header options
			if(!empty($this->_cookieOptions))
			{
				 curl_setopt($s,CURLOPT_COOKIE, $this->_cookieOptions);
			}
			
			 ob_start(); 
    		 $this->_result = curl_exec($s);
    		 $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE); 
    		 ob_end_clean();

             $this->_error=curl_error($s);

			 curl_close($s);
        }
        
        

        //set this to be true when its been attempted to execute
        $this->_executed = true;
    }
    
    public static function checkHttpStatus($status_code)
    {
    	$sc = (int)$status_code;
    	if($sc >= 200 && $sc < 400){
    		//was a success or redirect
    		return constant("STATUS_SUCCESS");
    	}
    	else if($sc >= 400 && $sc < 500)
    	{
    		return constant("STATUS_CLIENT_ERROR");
    	}
    	else if($sc >=500 && $sc <600)
    	{
    		return constant("STATUS_SERVER_ERROR");
    	}

    	//400-500 or anything else - issue
    	return constant("STATUS_UNKNOWN_ERROR");
    }
}

?>