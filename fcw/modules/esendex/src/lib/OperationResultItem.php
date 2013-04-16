<?php

/**
 * @package esendex.lib
 */

/**
 * Result from an add, update or delete operation
 */
class OperationResultItem extends ResultItem
{
	const ADD = 'add';
	const UPDATE = 'update';
	const DELETE = 'delete';
	
	/**
	 * HTTP codes are used to return the outcome of an operation.  200 means
	 * successful (OK), any other code means unsuccessful
	 */
	const OK = 200;
	
	private $outcome;
	private $operation;
	private $detail;
	private $service;
	
	function __construct($operation, $outcome, $service, $uri = null, $detail = null)
	{
		parent::__construct(esx_get_last_url_fragment($uri), $uri);
		
		$this->outcome = $outcome;
		$this->operation = $operation;
		$this->detail = $detail;
		$this->service = $service;
	}
	
	/**
	 * HTTP code describing the outcome of the operation, 200 is successful
	 *
	 * @return int
	 */
	function outcome() {
		return $this->outcome;
	}
	
	/**
	 * Type of the operation being add, update or delte.
	 *
	 * @return string
	 */
	function operation() {
		return $this->operation;
	}
	
	/**
	 * Any message associated with this operation.
	 *
	 * @return string
	 */
	function detail() {
		return $this->detail;
	}
	
	/**
	 * The service that made this executed operation
	 *
	 * @return string
	 */
	function service() {
		return $this->service;
	}
}