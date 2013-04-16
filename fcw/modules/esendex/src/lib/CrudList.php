<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class CrudList extends AbstractMetaClass
{
	private $addedItems;
	private $updatedItems;
	private $deletedItems;
	private $items;
	
	function __construct(array $addedItems, array $updatedItems, array $deletedItems, array $items)
	{
		$this->addedItems = $addedItems;
		$this->updatedItems = $updatedItems;
		$this->deletedItems = $deletedItems;
		$this->items = $items;
	}
	
	function addedItems() {
		return $this->addedItems;
	}
	
	function updatedItems() {
		return $this->updatedItems;
	}
	
	function deletedItems() {
		return $this->deletedItems;
	}
	
	function items() {
		return $this->items;
	}
}