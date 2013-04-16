<?php

/**
 * @package esendex.rest
 */

/**
 * The RestMessageBodyService can retrieve a messages content
 */
class RestMessageBodyService extends RestBaseService
{
	private $xmlparser;
	
    function __construct(IAuthentication $authentication, $isSecure = false, $certificate = "")
    {
        parent::__construct($authentication, $isSecure, $certificate);
        
        $this->xmlparser = new MessageBodyXmlParser();
    }
    
	function messagebodyFromId($msg_id)
	{
		if($msg_id == null)
		{
			throw new ArgumentException("parameter id is null");
		}
		else if(!is_string($msg_id))
		{
			throw new ArgumentException("parameter id is not a string");
		}
			
		$data = $this->httpUtil->get(
			$this->messagebodyUri($msg_id), $this->authentication, $this->port()
		);
		
		$messagebody = $this->xmlparser->parseMessagebody($data);
		
		return $messagebody;
		
	}
	
	/**
	 * Gets the messagebody of
	 *
	 * @param mixed $object
	 * @return MessageBody
	 */
	function messagebody($object)
	{
		$result = null;
		
		if($object instanceof AbstractRetrievedMessage)
		{
			$result = $this->messagebodyFromId($this->retrieveIdFromUri($object->bodyUri()));
		}
		else if(is_string($object))
		{
			$messageId = $this->retrieveIdFromUri($object);
			$result = $this->messagebodyFromId($messageId);
		}
		else
		{
			throw new ArgumentException("The given object is not a valid type for messagebody object parameter");
		}
		
		return $result;
	}
	
	/**
	 * return the message body id from a uri.  The uri should be in the format used
	 * in the esendex rest api:-
	 * 
	 * .../service/message_id
	 *
	 * @param string $uri
	 * @return string message id
	 */
	function retrieveIdFromUri($uri)
	{
		$fragments = split('/', $uri);
		end( $fragments );
		$message_id = current( $fragments );
		
		return $message_id;
	}
	
	
	function messagebodyUri($msg_id)
	{
		$uri = "http://".REST_ESENDEX_API_DOMAIN."/";
    	
    	if($this->mocking() == true)
    	{
        	$uri = $uri.REST_MOCK_KEYWORD."/";
    	}
    	
    	$uri = $uri.REST_MESSAGE_BODY_VERSION."/account/".$this->authentication->accountReference()."/messagebody/".$msg_id;
    	
    	return $uri;
	}
}

?>