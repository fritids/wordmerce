<?php

/**
 * @package esendex.lib.internal
 */

/**
 * Esendex PHP HTTP Utilities
 */
class HttpUtil
{
	private $isSecure, $certificate;

	function HttpUtil($isSecure = false, $certificate = "")
	{
		$this->isSecure = $isSecure;
		$this->certificate = $certificate;
	}

	function get($url, IAuthentication $authentication, $port = 80)
	{
		$client = new Net_HTTP_Client(REST_ESENDEX_API_DOMAIN, ESENDEX_API_PORT);
		$client->addHeader('Host', REST_ESENDEX_API_DOMAIN);
		$client->addHeader('Authorization', "Basic {$authentication->serialiseAsHttpHeader()}");
		$client->addHeader('User-Agent', ESENDEX_SDK_NAME);
		$client->get($url);
		 
		if(!$this->httpSuccessCode($client->getStatus()))
		{
			throw $this->getHttpException($client->getStatus());
		}
		 
		return $client->getBody();
	}

	function post($url, IAuthentication $authentication, $data, $port = 80 )
	{
		$client = new Net_HTTP_Client(REST_ESENDEX_API_DOMAIN, ESENDEX_API_PORT);
		$client->addHeader('Host', REST_ESENDEX_API_DOMAIN);
		$client->addHeader('Authorization', "Basic {$authentication->serialiseAsHttpHeader()}");
		$client->addHeader('User-Agent', ESENDEX_SDK_NAME);
		$client->addHeader('Content-Length', strlen($data));
		$client->post($url, $data);
		 
		if(!$this->httpSuccessCode($client->getStatus()))
		{
			throw $this->getHttpException($client->getStatus());
		}
		 
		return $client->getBody();
	}

	function delete($url, IAuthentication $authentication, $port = 80)
	{
		$client = new Net_HTTP_Client(REST_ESENDEX_API_DOMAIN, ESENDEX_API_PORT);
		$client->addHeader('Host', REST_ESENDEX_API_DOMAIN);
		$client->addHeader('Authorization', "Basic {$authentication->serialiseAsHttpHeader()}");
		$client->addHeader('User-Agent', ESENDEX_SDK_NAME);
		$client->delete($url);
		 
		if(!$this->httpSuccessCode($client->getStatus()))
		{
			throw $this->getHttpException($client->getStatus());
		}
		 
		return true;
	}

	function put($url, IAuthentication $authentication, $port = 80, $data)
	{
		$client = new Net_HTTP_Client(REST_ESENDEX_API_DOMAIN, ESENDEX_API_PORT);
		$client->addHeader('Host', REST_ESENDEX_API_DOMAIN);
		$client->addHeader('Authorization', "Basic {$authentication->serialiseAsHttpHeader()}");
		$client->addHeader('User-Agent', ESENDEX_SDK_NAME);
		$client->addHeader('Content-Length', strlen($data));
		$client->put($url, $data);
		 
		if(!$this->httpSuccessCode($client->getStatus()))
		{
			throw $this->getHttpException($client->getStatus());
		}
		 
		return $client->getBody();
	}

	function httpSuccessCode($code)
	{
		$intcode = (int)$code;
		 
		if($intcode >= 200 && $intcode < 300):
			return true;
		else:
			return false;
		endif;
	}

	function getHttpException($httpcode, $errormessage = '', array $info = null)
	{
		$httpcode = (int)$httpcode;
		 
		switch($httpcode)
		{
			case 400:
				$exceptionName = 'HttpBadRequestException';
				break;
			case 401:
				$exceptionName = 'HttpUnauthorisedException';
				break;
			case 402:
				$exceptionName = 'HttpPaymentRequiredException';
				break;
			case 403:
				$exceptionName = 'HttpUserCredentialsException';
				break;
			case 404:
				$exceptionName = 'HttpResourceNotFoundException';
				break;
			case 405:
				$exceptionName = 'HttpMethodNotAllowedException';
				break;
			case 408:
				$exceptionName = 'HttpRequestTimedOutException';
				break;
			case 411:
				$exceptionName = 'HttpLengthRequiredException';
				break;
			case 500:
				$exceptionName = 'HttpServerErrorException';
				break;
			case 501:
				$exceptionName = 'HttpNotImplementedException';
				break;
			case 503:
				$exceptionName = 'HttpServiceUnavailableException';
				break;
			default:
				$exceptionName = 'HttpException';
				break;
		}
		 
		return new $exceptionName($errormessage, $httpcode, $info);
	}
}
?>