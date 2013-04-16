<?php

/**
 * @package esendex.lib.authentication
 */

/**
 * Base class for authentication strategies
 */
abstract class AbstractAuthentication extends AbstractMetaClass implements IAuthentication
{
	private $accountReference;
	
	function __construct($accountReference)
	{
		$this->accountReference = $accountReference;
	}
	
	function accountReference() {
		return $this->accountReference;
	}
}