<?php

/**
 * @package esendex.rest
 */

/**
 * The RestMessageHeaderService is used to retrieve sent and 
 * received messages.
 */
class RestMessageHeaderService extends RestBaseService 
{    
    private $xmlparser;   
            
    function __construct(IAuthentication $authentication, $isSecure = false, $certificate = "")  
    {
        parent::__construct($authentication, $isSecure, $certificate);

        $this->xmlparser = new MessageHeaderXmlParser();
    }
    
    /**
     * Get detailed information on a messages from its ID.  The content will be a summary
     * of the text message.
     * 
     * @throws XmlException, HttpException
     */
    function message($msg_id) 
    {
        try
        {
        	$result = $this->httpUtil->get( 
        		$this->messageHeaderUri($msg_id), 
        		$this->authentication,
		        $this->port() 
	        );
        }
        catch(Exception $e)
        {
        	throw $e;
        }
        
        $message = $this->xmlparser->parse($result);
        
        return $message;
    }
    
    /**
     * returns the uri for obtaining the message header.
     */
    function messageHeaderUri($msg_id)
    {
    	$uri = "http://".REST_ESENDEX_API_DOMAIN."/";
    	
    	if($this->mocking() == true)
    	{
        	$uri = $uri.REST_MOCK_KEYWORD."/";
    	}
    	
    	$uri = $uri.REST_MESSAGE_HEADER_VERSION."/account/".$this->authentication->accountReference()."/messageheader/{$msg_id}";
    	
    	return $uri;
    }
}
?>
