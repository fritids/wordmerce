<?php

/**
 * @package esendex.lib.authentication
 */

/**
 * Authentication object for session authentication
 */
class SessionAuthentication extends AbstractAuthentication
{
	private $sessionId;
	
	function __construct($accountReference, $sessionId)
	{
		parent::__construct($accountReference);
		
		$this->sessionId = $sessionId;
	}
	
	function serialiseAsHttpHeader()
	{
		return base64_encode($this->sessionId);
	}
	
	function sessionId() {
		return $this->sessionId;
	}
}