<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class MessageBody extends AbstractMetaClass
{
	private $id;
	private $bodytext;
	
	function __construct($_id, $_bodytext)
	{
		$this->id($_id);
		$this->bodytext($_bodytext);
	}
	
	function id($id = null) {
		if($id != null) {
			$this->id = $id;
		}
		return $this->id;
	}
	
	function bodytext($bodytext = null) {
		if($bodytext != null) {
			$this->bodytext = $bodytext;
		}
		return $this->bodytext;
	}
}
?>