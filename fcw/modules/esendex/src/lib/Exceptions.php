<?php

/**
 * @package esendex.lib
 */

/**
 * 
 */
class EsendexException extends Exception
{
	private $exceptionInfo;
	
	function __construct($message= '', $code = null, array $_info = null)
	{
		parent::__construct($message, $code);
		
		$this->exceptionInfo($_info);
	}
	
	function exceptionInfo($exceptionInfo = null) { 
		if($exceptionInfo != null){
			$this->exceptionInfo = $exceptionInfo;
		}
		return $this->exceptionInfo; 
	}
	
	function __toString()
	{
		$error = array (
			'file' => $this->getFile(),
			'line' => $this->getLine(),
			'code' => $this->getCode(),
			'message' => $this->getMessage(),
			'stacktrace' => substr($this->getTraceAsString(), 0, 400),
		);
		
		if($this->exceptionInfo != null)
		{
			array_merge($error, $this->exceptionInfo());
		}
		
		return print_r($error, true);
	}
}

/**
 * Exception for an invalid argument to a method or function
 */
class ArgumentException extends EsendexException 
{
}

/**
 * XmlException is thrown when an error occurs when parsing XML. It could mean 
 * the XML was invalid or not in the format expected.
 */
class XmlException extends EsendexException 
{
    protected $xml;
    
    /**
     * Enter description here...
     *
     * @param string $message
     * @param int $code
     * @param string $xml, The XML string that failed parsing
     */
    function __construct($message, $code = 0, $xml = '') 
    {
        parent::__construct($message, $code);
        
        $this->xml = htmlspecialchars($xml, ENT_QUOTES);
    }
}


/**
 * HttpException is thrown when an error occurs when HTTP is used or
 * attempted.  The code property returns the HTTP error.
 */
class HttpException extends EsendexException 
{
}

/**
 * http status code 400
 */
class HttpBadRequestException extends HttpException 
{
}

/**
 * http status code 401
 */
class HttpUnauthorisedException extends HttpException 
{
}

/**
 * http status code 402
 */
class HttpPaymentRequiredException extends HttpException 
{
}

/**
 * http status code 403
 */
class HttpUserCredentialsException extends HttpException 
{
}

/**
 * http status code 404
 */
class HttpResourceNotFoundException extends HttpException 
{
}

/**
 * http status code 405
 */
class HttpMethodNotAllowedException  extends HttpException 
{
}

/**
 * http status code 408
 */
class HttpRequestTimedOutException extends HttpException 
{
}

/**
 * http status code 411
 */
class HttpLengthRequiredException extends HttpException 
{
}

/**
 * http status code 500
 */
class HttpServerErrorException extends HttpException 
{
}

/**
 * http status code 501
 */
class HttpNotImplementedException extends HttpException 
{
}

/**
 * http status code 503
 */
class HttpServiceUnavailableException extends HttpException 
{
}
