<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class InboxXmlParser
{
	const Originator = 'from';
	const Recipient = 'to';
	const Type = 'type';
	const Direction = 'direction';
	const Parts = 'parts';
	const Index = 'index';
	const Summary = 'summary';
	const ReadAt = 'readat';
	const ReadBy = 'readby';
	const MESSAGE_HEADER = 'messageheader';
	
	function parseInboxXml($xml, &$lastIndex = null)
	{
		$messageParser = new MessageHeaderXmlParser();
		$dom = $this->loadDom($xml);
        $messages = array();
        
        $nodelist = $this->messageHeaderElements($dom);
        
        $lastIndex = $this->lastIndex($dom->documentElement);
        
		for ($i = 0; $i < $nodelist->length; $i++)
		{
			$messages[] = $messageParser->parseMessageHeaderXml(
				$nodelist->item($i), AbstractMessage::Inbound, 'InboxMessage'
			);
		}
		
		return $messages;
	}

	function loadDom($xml)
	{
		$dom = new DomDocument();
        $result = $dom->loadXML($xml);
        
        if($result == false)
        {
        	throw new XmlException("failure to load xml [{$xml}]");
        }
        
        return $dom;
	}
	
	/**
	 * Returns the message header elements from an inbox dom document
	 *
	 * @param DomDocument $domdoc
	 * @return DOMNodeList
	 */
	function messageHeaderElements(DomDocument $domdoc)
	{
		if($domdoc == null)
			throw new ArgumentException('messageHeaderElements() $domdoc is null');
			
		// get the document root
        $docroot = $domdoc->documentElement;
        
        // get all message header elements
        $itemsNode = esx_single_element($docroot, 'items');
        $msgHeaderList = $itemsNode->getElementsByTagname('messageheader');
        
        return $msgHeaderList;
	}
	
	/**
	 * Retrieve the last index value from an inbox response.  It
	 * takes the root element of the response ie <inbox> and works
	 * from there.
	 *
	 * @param DomElement $rootnode
	 * @return int
	 */
	function lastIndex(DomElement $rootnode)
	{
		if($rootnode == null)
			throw new ArgumentException('lastIndex() $rootnode is null');
			
		$lastIndexNode = esx_single_element($rootnode, 'lastmessageindex');
		$lastIndex = (int)$lastIndexNode->nodeValue;
        
        return $lastIndex;
	}
}
?>