<?php

/**
 * @package esendex.lib
 */

/**
 * MessageHeader 
 *
 * @copyright Copyright (c) 2004/2008 Esendex
 * @link http://www.esendex.com/support
 * @author Esendex
 */
class MessageHeader extends AbstractRetrievedMessage
{
	private $username;
    private $lastStatusAt;
    private $sentAt;
	private $deliveredAt;
	
    function __construct ()
    {
    }
	
	/**
	 * date/time the message was sent at
	 *
	 * @param unknown_type $sentAt
	 * @return unknown
	 */
	function sentAt($sentAt = null) {
		if($sentAt != null) {
			$this->sentAt = $sentAt;
		}
		return $this->sentAt; 
	}
	
	function lastStatusAt($lastStatusAt = null) {
		if($lastStatusAt != null){
			$this->lastStatusAt = $lastStatusAt;
		}
		return $this->lastStatusAt; 
	}

	/**
	 * username of the sender of the message
	 *
	 * @param string $username
	 * @return string
	 */
	function username($username = null) {
		if($username != null){
			$this->username = $username;
		}
		return $this->username; 
	}
	
	/**
	 * date/time the message was delivered at
	 * 
	 * @param string deliveredAt
	 * @return string
	 */
	function deliveredAt($deliveredAt = null) {
		if($deliveredAt != null) {
			$this->deliveredAt = $deliveredAt;
		}
		return $this->deliveredAt;
	}
}

?>
