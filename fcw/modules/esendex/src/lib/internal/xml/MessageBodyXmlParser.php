<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class MessageBodyXmlParser
{
	function parseMessagebody($xml)
	{
		$dom = new DomDocument();
		$dom->loadXML($xml);
		
		$root = $dom->documentElement;
		
		$msgId = $root->getAttribute('id');
		$bodyNode = esx_single_element($root, 'bodytext');
		
		$msgBody = new MessageBody($msgId, $bodyNode->nodeValue);
		
		return $msgBody;
	}
}
?>