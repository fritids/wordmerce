<?php

/**
 * @package esendex.lib.authentication
 */

/**
 * Username and password authentication
 */
class LoginAuthentication extends AbstractAuthentication
{
	private $username, $password;
	
	function __construct($accountReference, $username, $password)
	{
		parent::__construct($accountReference);
		
		$this->username = $username;
        $this->password = $password;
	}
	
	function serialiseAsHttpHeader()
	{
		return base64_encode("{$this->username()}:{$this->password()}");
	}
	
	function username() {
		return $this->username;
	}
	
	function password() {
		return $this->password;
	}
}