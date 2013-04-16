<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class SessionXmlParser
{
	/**
	 * Retrieves the ID in the session XML
	 *
	 * @param string $xml
	 * @return string
	 */
	function deserialiseSession($xml)
	{
		$dom = new DomDocument();
		$dom->loadXML($xml);
		$node = $dom->documentElement;
		
		$idNode = esx_single_element($node, 'id');
		
		return $idNode->nodeValue;
	}
}