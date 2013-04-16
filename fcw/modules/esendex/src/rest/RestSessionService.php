<?php

/**
 * @package esendex.rest
 */

/**
 *
 */
class RestSessionService extends RestBaseService
{
	const SERVICE = 'session';
	const SESSION_CONSTRUCTOR = 'session/constructor';
	const SESSION_DELETE = 'delete';
	
	const SERVICE_VERSION = 'v0.3';
	
	private $sessionParser;
	
	function __construct($isSecure = false, $certificate = '')
	{
		parent::__construct(null, $isSecure, $certificate);
		
		$this->sessionParser = new SessionXmlParser();
	}
	
	/**
	 * Retrieve a SessionAuthentication instance
	 *
	 * @param string $accountRef
	 * @param string $username
	 * @param string $password
	 * @return SessionAuthentication
	 */
	function startSession($accountRef, $username, $password)
	{
		$login = new LoginAuthentication($accountRef, $username, $password);
		
		return $this->startSessionUsingLogin($login);
	}
	
	function startSessionUsingLogin(LoginAuthentication $authentication)
	{
		$uri = $this->serviceUri(
			self::SESSION_CONSTRUCTOR, self::SERVICE_VERSION, $authentication->accountReference()
		);
		
		$result = $this->httpUtil->post($uri, $authentication, '', $this->port());
		
		$session = new SessionAuthentication( 
			$authentication->accountReference(), $this->sessionParser->deserialiseSession($result) 
		);
		
		return $session;
	}
	
	/**
	 * Stop a session so it is not usuable anymore
	 *
	 * @param mixed $key
	 * @return boolean
	 */
	function stopSession($key)
	{
		$sessionId = '';
		
		if($key instanceof SessionAuthentication)
		{
			$sessionId = $key->sessionId();
		}
		else if(is_string($key))
		{
			$sessionId = $key;
		}
		
		$uri = $this->serviceUri(self::SESSION_DELETE, self::SERVICE_VERSION, $accountRef);
		
		$uri = "{$uri}/{$sessionId}";
		
		$httpStatusCode = $this->httpUtil->delete( $uri, $this->username, $this->password, $this->port() );
		
		return $httpStatusCode == 200;
	}
}
