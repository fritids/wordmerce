<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class DispatchMessage extends AbstractMessage
{
	const ENGLISH_LANGUAGE = "en-gb";
	
	private $validityPeriod;
	private $body;
	private $language;
	
	function __construct( $originator, $recipient, $_body, $type, $_validityPeriod=0, $_language = self::ENGLISH_LANGUAGE)
	{
		$this->originator( $originator );
		$this->recipient( $recipient );
		$this->body( $_body );
		$this->type( $type );
		$this->validityPeriod( $_validityPeriod );
		$this->language( $_language );
	}
	
	function validityPeriod($validityPeriod = null) {
		if($validityPeriod != null) {
			$this->validityPeriod = (int)$validityPeriod;
		}
		return $this->validityPeriod; 
	}
	
	function body($body = null) {
		if($body != null) {
			$this->body = $body;
		}
		return $this->body;
	}
	
	/**
	 * If the type of message is Voice then the language of the message
	 * can be set so it will be read out to the user in a native way.
	 *
	 * @param string $language
	 * @return string
	 */
	function language($language = null) {
		if($language != null) {
			$this->language = $language;
		}
		return $this->language; 
	}
}
?>