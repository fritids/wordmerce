<?php

/**
 * @package esendex.lib
 */

/**
 * A base class for all retrieved messages
 */
abstract class AbstractRetrievedMessage extends AbstractMessage
{
    private $receivedAt;
    private $parts;
    private $bodyUri;
    private $summary;
    private $readat;
	private $readby;
	private $index;
	
    /**
     * How many parts this message has, more than one if it is a long message
     *
     * @param int $parts
     * @return int
     */
	function parts($parts = null) {
		if($parts != null) {
			$this->parts = (int)$parts;
		}
		return $this->parts; 
	}
	
	/**
	 * date/time the message was received at
	 *
	 * @param string $receivedAt
	 * @return string
	 */
	function receivedAt($receivedAt = null) {
		if($receivedAt != null){
			$this->receivedAt = $receivedAt;
		}
		return $this->receivedAt; 
	}
	
	/**
	 * full uri to retrieve the message body
	 *
	 * @param string $bodyUri
	 * @return string
	 */
	function bodyUri($bodyUri = null) {
		if($bodyUri != null){
			$this->bodyUri = $bodyUri;
		}
		return $this->bodyUri; 
	}	
	
	/**
     * returns the string summary of the sms.  Remember to escape 
     * this string if it is to be outputted to a webpage or the 
     * site could be vulnerable to code injection.
     * 
     * @property
     * 
     * @param string $body
     * @return string
     */
	function summary($summary = null) {
		if($summary != null) {
			$this->summary = $summary;
		}
		return $this->summary;
	}
	
	/**
	 * index of the onbox message.  note that the index does not appear on
	 * MessageHeader objects.
	 * 
	 * @param int $index
	 * @return unknown
	 */
	function index($index = null) {
		if($index != null) {
			$this->index = $index;
		}
		return $this->index;
	}
	
	/**
	 * The date/time the message was read at
	 *
	 * @param string $readAt
	 * @return string
	 */
	function readAt($readAt = null) {
		if($readAt != null) {
			$this->readat = $readAt;
		}
		return $this->readat;
	}
	
	/**
	 * The username of the user who read the message
	 *
	 * @param string $readBy
	 * @return string
	 */
	function readBy($readBy = null) {
		if($readBy != null) {
			$this->readby = $readBy;
		}
		return $this->readby;
	}
}
?>