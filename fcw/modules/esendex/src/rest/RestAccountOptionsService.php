<?php

/**
 * @package esendex.rest
 */

/**
 * 
 */
class RestAccountOptionsService extends RestBaseService
{
	const ACCOUNT_OPTIONS_SERVICE = 'options';
	const ACCOUNT_OPTIONS_SERVICE_VERSION = 'v0.2';
	
	private $accountOptionsParser;
	
	function __construct(IAuthentication $authentication, $isSecure = false, $certificate = '')
	{
		parent::__construct($authentication, $isSecure, $certificate);
		
		$this->accountOptionsParser = new AccountOptionsXmlParser();
	}
	
	/**
	 * Return the options for an account
	 *
	 * @param string $accountReference
	 * @return AccountOptions
	 */
	function getAccountOptions($accountReference = null)
	{
		if($accountReference == null)
		{
			$accountReference = $this->authentication->accountReference();
		}
		
		$uri = $this->serviceUri(
			self::ACCOUNT_OPTIONS_SERVICE, self::ACCOUNT_OPTIONS_SERVICE_VERSION, $accountReference
		);
		
		$result = $this->httpUtil->get($uri, $this->authentication, $this->port());
		
		return $this->accountOptionsParser->deserialiseAccountOptions($result);
	}
	
	/**
	 * Save AccountOptions object
	 *
	 * @param AccountOptions $accountOptions
	 * @param string $accountReference
	 * @return boolean
	 */
	function saveAccountOptions(AccountOptions $accountOptions, $accountReference = null)
	{
		if($accountReference == null)
		{
			$accountReference = $this->authentication->accountReference();
		}
		
		$uri = $this->serviceUri(
			self::ACCOUNT_OPTIONS_SERVICE, self::ACCOUNT_OPTIONS_SERVICE_VERSION, $accountReference
		);
		
		$requestContent = $this->accountOptionsParser->serialiseAccountOptions($accountOptions);
		
		$result = $this->httpUtil->put($uri, $this->authentication, $this->port(), $requestContent);
		
		return true;
	}
}
