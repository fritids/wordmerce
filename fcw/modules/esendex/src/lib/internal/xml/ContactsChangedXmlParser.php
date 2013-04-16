<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class ContactsChangedXmlParser
{
	/**
	 * Deserialise XML into a CrudList object
	 *
	 * @param string $xml
	 * @return CrudList
	 */
	function deserialiseContactsChanged($xml)
	{
		$dom = new DomDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		
		$root = $dom->documentElement;
		
		$itemsElement = esx_single_element($root, 'items');
		
		$contactsArray['add'] = array();
		
		foreach(array('added', 'updated', 'deleted') as $elementName)
		{
			$tempArray = array();
			$nodeList = esx_single_element($itemsElement, $elementName)->getElementsByTagName('contact');

			foreach($nodeList as $node)
			{
				$tempArray[] = $this->deserialiseContact($node);
			}
			
			$contactsArray[$elementName] = $tempArray;
		}
		
		$list = new CrudList(
			$contactsArray['added'], 
			$contactsArray['updated'], 
			$contactsArray['deleted'],
			array_merge(
				$contactsArray['added'], 
				$contactsArray['updated'], 
				$contactsArray['deleted']
			)
		);
		
		return $list;
	}
	
	/**
	 * Deserialise a DomElement to a ResultItem
	 *
	 * @param DomElement $element
	 * @return ResultItem
	 */
	function deserialiseContact(DomElement $element)
	{
		$uri = '';
		
		// a deleted contact does not have a uri
		if($element->hasAttribute('uri'))
		{
			$uri = $element->getAttribute('uri');
		}
		
		$result = new ResultItem($element->getAttribute('id'), $uri);
		
		return $result;
	}
}