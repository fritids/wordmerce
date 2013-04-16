<?php

/**
 * @package esendex.rest
 */

/**
 * The RestDispatchService can be used to send SMS and Voice messages
 */
class RestDispatchService extends RestBaseService 
{   
	/**
	 * The version string of the send service which will be used in the service url.
	 */
	const DISPATCH_VERSION = 'v0.2';
	
    private $xmlparser;
    
    /**
     * Construct a new RestDispatchService
     *
     * @param string $accountRef Your account reference, for example. EX1234567
     * @param string $username The username to access your account
     * @param string $password The password to access your account
     * @param boolean $mocking true if the service is using the mock services
     * @param boolean $isSecure true if the service is using SSL 
     * @param string $certificate path where the certificate lives
     */
    function __construct(IAuthentication $authentication, $isSecure = false, $certificate = "")
    {
        parent::__construct($authentication, $isSecure, $certificate);
        
        $this->xmlparser = new DispatchXmlParser();
    }
    
    /**
     * send an sms message and returns an array of MessageResult object
     *
     * @param Message $message
     * @return unknown
     */
    function send(DispatchMessage $message)
    {
        esx_debug("starting dispatch message");

        // turn the message object into xml
        $xml = $this->xmlparser->encodeMessage($message);
        
        // send the xml in an http post to the rest service
        $result = $this->httpUtil->post( 
        	$this->dispatchUri(), $this->authentication, $xml, $this->port() 
        );
                          
        $arr = $this->xmlparser->parseMessageResults($result);
        
        if(count($arr) >= 1)
        {
        	esx_debug("dispatch successful", array('message_id' => $arr[0]->id()));
        	
        	return $arr[0];
        }
        else
        {
        	$exception = new EsendexException("Error parsing the dispatch result", null, array('data_returned' => $result));
        	esx_log_exception($exception);
        	
        	throw $exception;
        }
    }
    
    function dispatchUri()
    {
    	$uri = "http://".REST_ESENDEX_API_DOMAIN."/";
    	
    	if($this->mocking() == true)
    	{
        	$uri = $uri.REST_MOCK_KEYWORD."/";
    	}
    	
    	$uri = $uri.self::DISPATCH_VERSION."/account/".$this->authentication->accountReference()."/messagedispatcher";
    	
    	return $uri;
    }
}
?>
