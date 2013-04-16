<?php

/**
 * @package esendex.lib.internal
 */

/**
 * Base class for rest services
 */
abstract class RestBaseService
{
    protected $httpUtil;
    protected $authentication;
    protected $port;
    protected $mocking;
    protected $secure;
    
    function __construct($authentication = null, $isSecure = false, $certificate = '') 
    {
        $this->mocking = false;
        $this->secure = $isSecure;
        $this->authentication = $authentication;
        
        $this->httpUtil = new HttpUtil($isSecure, $certificate);
    }
	
	/**
	 * Set the port to access the rest service.
	 * 
	 * @property
	 *
	 * @param int $port
	 * @return int
	 */
	function port($port = null) {
		if($port != null){
			$this->port = (int)$port;
		}
		return $this->port; 
	}
	
	/**
     * If mocking is true then calls into the Esendex system will use the mock api and
     * will not have an effect and you will not be charged which is useful for 
     * testing purposes as it simulates actual results.
     *
     * @var boolean
     */
	function mocking($mocking = null) {
		if($mocking != null){
			$this->mocking = (bool)$mocking;
		}
		return $this->mocking; 
	}
	
	/**
	 * Get the protocol depending on whether the instance is secure or not
	 *
	 * @return string
	 */
	function protocol()
	{
		if($this->secure == true)
		{
			return 'https';
		}
		else
		{
			return 'http';
		}
	}
	
	function serviceUri($serviceName, $version = REST_DEFAULT_VERSION, $accountref = null)
	{
		$domain = REST_ESENDEX_API_DOMAIN;
		$mockingFragment = '';
		
		if($accountref == null)
		{
			$accountref = $this->accountRef;
		}
		
		$uri = "{$this->protocol()}://{$domain}/";
		
		if($this->mocking() == true)
		{
			$uri = $uri.REST_MOCK_KEYWORD.'/';
		}
		
		$uri = $uri."{$version}/account/{$accountref}/{$serviceName}";
		
		return $uri;
	}
}
?>
