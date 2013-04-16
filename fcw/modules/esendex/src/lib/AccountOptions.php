<?php

/**
 * @package esendex.lib
 */

/**
 * Options for an account
 */
class AccountOptions extends AbstractMetaClass
{
	private $longMessageingEnabled = false;
	
	function longMessageingEnabled($longMessageingEnabled = null) { 
		if($longMessageingEnabled != null){
			if(is_bool($longMessageingEnabled)) {
				$this->longMessageingEnabled = $longMessageingEnabled;
			}
			else {
				throw new ArgumentException('longMessageingEnabled must be a boolean');
			}
		}
		return $this->longMessageingEnabled; 
	}
}