<?php

/**
 * @package esendex.rest
 */

/**
 * service to check that your username, password and account reference 
 * authenticate in the esendex system.
 */
class RestCheckAccessService extends RestBaseService
{
	const SERVICE = 'accesscheck';
	
	/**
	 * Check that your username, password and account reference is valid
	 * 
	 * @param string $accountReference
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	function checkAccess($accountReference, $username, $password)
	{
		return $this->checkAuthenticationAccess(
			new LoginAuthentication($accountReference, $username, $password)
		);
	}
	
	/**
	 * Check if a session is valid, a valid session ID might have timed out and have
	 * been deleted.  By calling this method the session will be
	 * 'kept alive'
	 *
	 * @param SessionAuthentication $authentication
	 * @return boolean
	 */
	function checkSessionAccess(SessionAuthentication $authentication)
	{
		return $this->checkAuthenticationAccess($authentication);
	}
	
	/**
	 * Check that any authentication is valid
	 *
	 * @param IAuthentication $authentication
	 * @return boolean
	 */
	function checkAuthenticationAccess(IAuthentication $authentication)
	{
		try
		{
			$accessCheckUri = $this->serviceUri (
				self::SERVICE, REST_ACCESS_CHECK_VERSION, $authentication->accountReference()
			);
			
			$this->httpUtil->get($accessCheckUri, $authentication, $this->port());
			
			// if it hasn't thrown an exception yet then all is well
			return true;
		}
		catch(Exception $e)
		{
			esx_log_exception($e);
			
			return false;
		}
	}
}