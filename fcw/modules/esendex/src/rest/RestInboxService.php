<?php

/**
 * @package esendex.rest
 */

/**
 * The RestInboxService is used to retrieve an account's inbox
 */
class RestInboxService extends RestBaseService 
{   
	const INBOX_SERVICE = 'inbox';
	const INBOX_SERVICE_VERSION = 'v0.2';
	
    private $xmlparser;
    private $inboxUri;
    
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
    public function __construct( IAuthentication $authentication, $isSecure = false, $certificate = "" )
    {
    	parent::__construct( $authentication, $isSecure, $certificate ); 
    						
    	$this->inboxUri = $this->buildUri($this->mocking);
    	$this->xmlparser = new InboxXmlParser();
    }
    
    /**
     * 
     * @access private
     *
     * @param boolean $mocking
     * @return string the inbox uri
     */
    function buildUri($mocking)
    {
    	$tempUri = '';
    	
    	$tempUri = "http://".REST_ESENDEX_API_DOMAIN."/";
    	
    	if($mocking == true)
    	{
    		$tempUri = $tempUri."mock/";
    	}
    	
    	$tempUri = $tempUri.REST_INBOX_VERSION."/account/".$this->authentication->accountReference()."/inbox";
    	
    	return $tempUri;
    }
    
    function latest($max = null, &$lastIndex = null)
    {
    	esx_debug('starting get latest inbox');
    	
    	$getLatestUri = $this->inboxUri;
    	
    	if($max != null && is_int($max))
    	{
    		$getLatestUri = $getLatestUri."?max={$max}";
    	}
    	
    	try
    	{
	    	$data = $this->httpUtil->get(
	    		$getLatestUri, $this->authentication, $this->port()
    		);
	    	
	    	$inbox = $this->xmlparser->parseInboxXml($data, $lastIndex);
    	}
    	catch(EsendexException $e)
    	{
    		esx_log_exception($e);
    		
    		throw $e;
    	}
    	
    	esx_debug('get latest inbox successful');
    	
    	return $inbox;
    }
    
    function startingFromIndex($index, &$lastIndex = null)
    {
    	if(!is_int($index))
    		throw new ArgumentException('The index parameter must be a non null int');
    		
    	$fromIndexUri = $this->inboxUri;
    	
    	if($index != null && is_int($index))
    	{
    		$fromIndexUri = $fromIndexUri."?lastindex={$index}";
    	}
    	
    	$data = $this->httpUtil->get($fromIndexUri, $this->authentication, $this->port());
    	
    	$inbox = $this->xmlparser->parseInboxXml($data, $lastIndex);
    	
    	return $inbox;
    }
    
    /**
     * Delete an inbox message using its id
     *
     * @param string $messageId
     * @return bool
     */
    function deleteInboxMessage($messageId)
    {
    	$uri = $this->serviceUri(
    		self::INBOX_SERVICE, self::INBOX_SERVICE_VERSION, $this->authentication->accountReference()
    	);
    	
    	$uri = "{$uri}/{$messageId}";
    	
    	return $this->httpUtil->delete($uri, $this->authentication, $this->port()) == 200;
    }
}
?>