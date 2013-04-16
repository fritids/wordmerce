<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class ContactsBatchResult extends ResultList
{
	private $contacts;
	
	function __construct(array $resultItems, array $contacts)
	{
		parent::__construct($resultItems);
		
		$this->contacts = $contacts;
	}
}