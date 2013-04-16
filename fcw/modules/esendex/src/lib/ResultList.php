
<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class ResultList extends CrudList
{
	private $successfulItems;
	private $failedItems;
	
	/**
	 * @param array $resultArray array of OperationResultItem
	 */
	function __construct(array $resultArray)
	{
		$addedItems = array();
		$updatedItems = array();
		$deletedItems = array();
		
		$this->successfulItems = array();
		$this->failedItems = array();
		
		foreach($resultArray as $resultItem)
		{
			// a ResultList can only contain instances of OperationResultItem
			if(!($resultItem instanceof OperationResultItem))
			{
				throw new ArgumentException("array must only contain objects of type OperationResultItem");
			}
			
			// add the object references to arrays
			if($resultItem->outcome() == OperationResultItem::OK)
			{
				$this->successfulItems[] = $resultItem; 
				
				switch($resultItem->operation())
				{
					case OperationResultItem::ADD:
						$addedItems[] = $resultItem;
						break;
					case OperationResultItem::UPDATE:
						$updatedItems[] = $resultItem;
						break;
					case OperationResultItem::DELETE:
						$deletedItems[] = $resultItem;
						break;
				}
			}
			else
			{
				$this->failedItems[] = $resultItem;
			}
		}
		
		parent::__construct($addedItems, $updatedItems, $deletedItems, $resultArray);
	}
	
	/**
	 * Get the array of successful OperationResultItem items
	 *
	 * @return array of OperationResultItem
	 */
	function successes() {
		return $this->successfulItems;
	}
	
	/**
	 * Get the array of unsuccessful OperationResultItem items
	 *
	 * @return array of OperationResultItem
	 */
	function failures() {
		return $this->failedItems;
	}
}
