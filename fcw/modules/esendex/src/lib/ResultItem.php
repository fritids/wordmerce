<?php

/**
 * @package esendex.lib
 */

/**
 * ResultItem is the result of an operation but without context of whether
 * it was successful or not, typically an unsuccessful operation would not
 * return a ResultItem.  ResultItem has id and uri fields to retrieve the 
 * target of the operation.
 */
class ResultItem extends AbstractMetaClass
{
	protected $id;
	protected $uri;
	
	function __construct($id, $uri)
	{
		$this->id = $id;
		$this->uri = $uri;
	}
	
	/**
     * Unique identifier for the object
     * 
     * @property
     *
     * @return string id
     */
	function id() { 
		return $this->id; 
	}
    
    /**
     * The uri to retrieve the object
     * 
     * @property
     *
     * @return string uri
     */
	function uri() {
		return $this->uri;
	}
}