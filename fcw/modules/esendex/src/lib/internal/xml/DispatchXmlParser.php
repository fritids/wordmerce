<?php

/**
 * @package esendex.lib.internal.xml
 */

/**
 * 
 */
class DispatchXmlParser 
{
	/**
     * encode a Message object into an xml string.
     *
     * @arg $message
     * @throws XmlException
     */
	function encodeMessage(DispatchMessage $message)
	{
		try 
        {
            $dom = new DomDocument('1.0', 'UTF-8');
            $root = $dom->createElement("message"); 
            
            // build the body element
            $body = null;
            
            if($message->type() == AbstractMessage::VoiceType)
            {
	            $body = $dom->createElement("body"); 
	            $body->appendChild( $dom->createElement("lang", $message->language()) );
	            $body->appendChild( $dom->createElement("text", $message->body()) );
            }
            else if($message->type() == AbstractMessage::SmsType)
            {
            	$body = $dom->createElement("body", $message->body()); 
            }
            else
            {
            	throw new Exception('Invalid SMS type');
            }
            
            // only add the from field if the originator property is not empty
            if($message->originator() != null && strlen($message->originator()) > 0) {
            	$root->appendChild( $dom->createElement("from", $message->originator()) );
            }
            
            $root->appendChild( $dom->createElement("to", $message->recipient()) );
            
            $root->appendChild( $dom->createElement("type", $message->type()) );
            $root->appendChild( $body );
            
            if($message->validityPeriod() > 0) {
            	$root->appendChild( $dom->createElement("validity", (int)$message->validityPeriod()) );
            }
            $dom->appendChild($root);
            
            return $dom->saveXML();
        }
        catch(Exception $e) 
        {
            throw new XmlException($e->getMessage(), $e->getCode());
        }
	}
	
	function parseMessageResults($xml)
	{    	
    	$results = array();
            
        // read the contents of the xml into the document
        $dom = new DomDocument();
        $dom->loadXML($xml);

        // get the header array
        $docroot = $dom->documentElement;
            
        if($docroot == null) {
        	throw new Exception("could not find the document root.");
        }
        $headerList = $docroot->getElementsByTagName('messageheader');
        
        // list through all the message elements and create a
        // MessageResult object from it then add it to the array
    	for ($i = 0; $i < $headerList->length; $i++)
        { 
        	$msgResult = new ResultItem( $headerList->item($i)->getAttribute("id"), 
        								 $headerList->item($i)->getAttribute("uri") );
			
            array_push($results, $msgResult);
        }
            
        return $results;
	}
}
?>