<?php

/**
 * @package esendex.lib
 */

/**
 * A base class for all messages
 */
abstract class AbstractMessage extends AbstractMetaClass
{    
	const Inbound = 'Inbound';
	const Outbound = 'Outbound';
	
	const SmsType = "SMS";
	const VoiceType = "Voice";
	
	private $id;
	private $originator;
    private $recipient;
    private $status;
    private $type;
    private $direction;
    private $submittedAt;
    
    /**
     * Unique identifier for the message which can be used reference
     * the message across service.
     * 
     * @property
     *
     * @param string $id
     * @return string
     */
	function id($id = null) { 
		if($id != null){
			$this->id = $id;
		}
		return $this->id; 
	}
	
	/**
	 * This originator is who the message is sent from and will
	 * be displayed to the reader.
	 *
	 * @param unknown_type $originator
	 * @return unknown
	 */
	function originator($originator = null) { 
		if($originator != null){
			$this->originator = $originator;
		}
		return $this->originator; 
	}
	
	/**
     * message transport type, should be 'SMS' or 'Voice'
     * 
     * @property 
     */
	function type($type = null) {
		if($type != null){
			if($type == self::SmsType || $type == self::VoiceType) {
				$this->type = $type;
			}
			else {
				throw new ArgumentException("type() value was '{$type}' and must be either 'SMS' or 'Voice'");
			}
		}
		return $this->type; 
	}

	/**
	 * direction (Inbound/Outbound) of the message
	 *
	 * @param unknown_type $direction
	 * @return unknown
	 */
	function direction($direction = null) {
		if($direction != null){
			if($direction == self::Inbound || $direction == self::Outbound) {
				$this->direction = $direction;
			}
		}
		return $this->direction; 
	}

	/**
	 * current status of the message
	 *
	 * @param unknown_type $status
	 * @return unknown
	 */
	function status($status = null) {
		if($status != null){
			$this->status = $status;
		}
		return $this->status; 
	}
	
	/**
	 * recipient of the message, this will be their phone number
	 *
	 * @param unknown_type $recipient
	 * @return unknown
	 */
	function recipient($recipient = null) 
	{
		if($recipient != null)
		{
			if($recipient == '')
			{
				throw new ArgumentException('The recipient given to this message is empty');
			}
			
			$this->recipient = $recipient;
		}
		return $this->recipient; 
	}

	function submittedAt($submittedAt = null) {
		if($submittedAt != null){
			$this->submittedAt = $submittedAt;
		}
		return $this->submittedAt; 
	}
}
?>