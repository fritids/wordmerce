<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 *
 */
class MessageHeaderXmlParser
{
	const Originator = 'from';
	const Recipient = 'to';
	const Type = 'type';
	const Direction = 'direction';
	const Parts = 'parts';
	const Username = 'username';
	const Summary = 'summary';
	const MESSAGE_HEADER = 'messageheader';
	const Status = 'status';
	const SentAt = 'sentat';
	const SubmittedAt = 'submittedat';
	const LastStatusAt = 'laststatusat';
	const ReceivedAt = 'receivedat';
	const READ_BY = 'readby';
	const READ_AT = 'readat';
	const INDEX = 'index';
	const DELIVERED_AT = 'deliveredat';

	function parse($xml)
	{
		$dom = $this->loadDom($xml);
		$node = $dom->documentElement;

		return $this->parseMessageHeaderXml($node);
	}
	
	function createMessage($messageType)
	{
		if($messageType == null)
		{
			return new MessageHeader();
		}
		else
		{
			return new $messageType();
		}
	}
	
	function parseMessageHeaderXml(DomElement $node, $direction = null, $messageType = null)
	{
		// make a dictionary from the children nodes
		$elements = esx_element_array( $node->childNodes );
		$params = array();

		$object = $this->createMessage($messageType);
		
		if($direction != null)
		{
			$object->direction($direction);
		}
		else
		{
			$object->direction( $elements[self::Direction] );
		}
		
		// the body uri is an attribute on the body element
		$bodyNode = esx_single_element($node, 'body');
		$bodyUri = $bodyNode->getAttribute('uri');

		// get the message if from the attribute on the messageheader element
		$msgId = $node->getAttribute('id');

		$object->id( $msgId );
		$object->summary( $elements[self::Summary] );
		$object->bodyUri( $bodyUri );
		$object->originator( $elements[self::Originator] );
		$object->recipient( $elements[self::Recipient] );
		$object->type( $elements[self::Type] );
		
		$object->parts( $elements[self::Parts] );
		
		if(array_key_exists(self::SubmittedAt, $elements)) {
			$object->submittedAt( $elements[self::SubmittedAt] );
		}

		// some elements written depending on the direction
		if($object->direction() == AbstractMessage::Inbound) 
		{
			$object->receivedAt( $elements[self::ReceivedAt] );
				
			// readby and readat may not exist if they have not been read
			if(array_key_exists(self::READ_AT, $elements)) {
				$object->readAt( $elements[self::READ_AT] );
			}
			if(array_key_exists(self::READ_BY, $elements)) {
				$object->readBy( $elements[self::READ_BY] );
			}
		}
		else // outbound
		{
			$object->username( $elements[self::Username] );
			$object->status( $elements[self::Status] );
			
			// should always be present for outbound
			if(array_key_exists(self::INDEX, $elements)) {
				$object->index( $elements[self::INDEX] );
			}
				
			if(array_key_exists(self::LastStatusAt, $elements)) {
				$object->lastStatusAt( $elements[self::LastStatusAt] );
			}
			if(array_key_exists(self::SentAt, $elements)) {
				$object->sentAt( $elements[self::SentAt] );
			}
			if(array_key_exists(self::DELIVERED_AT, $elements)) {
				$object->deliveredAt( $elements[self::DELIVERED_AT] );
			}
		}

		return $object;
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
}
?>