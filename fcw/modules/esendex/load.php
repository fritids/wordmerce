<?php

define('ESENDEX_HOME', dirname(__FILE__)."/src/");

require_once ESENDEX_HOME.'esendex_autoload.php';

//$esendex = new esendex;

//print_r($esendex->send_sms('Andy Pandy', '447843827989', 'Yo! Testing this shiz!'));

//$esendex->check_sms_status('7bb96db2-77d3-4d72-ab2e-aa6b0a12d211');

class esendex{

	private $loginCredentials;
	
	function esendex(){
		
		$this->__construct();
		
	}
	
	function __construct(){
	
		global $config;
		
		$account_number = $config['esendex']['account_number'];
		
		$username = $config['esendex']['username'];
		
		$password = $config['esendex']['password'];
				
		$this->loginCredentials = new LoginAuthentication($account_number, $username, $password);
		
	}
	
	function send_sms($from, $to, $message){
		
		$dispatcherService = new RestDispatchService($this->loginCredentials);
		
		$messageToSend = new DispatchMessage($from, $to, $message, AbstractMessage::SmsType);
		
		return $dispatcherService->send($messageToSend);
		
		/* 
		
		returns:
		
		ResultItem Object ( [id:protected] => 7bb96db2-77d3-4d72-ab2e-aa6b0a12d211 [uri:protected] => http://api.esendex.com/v1.0/messageheaders/7bb96db2-77d3-4d72-ab2e-aa6b0a12d211 ) 
		
		*/
		
	}
	
	function check_sms_status($id){
		
		$sentMessageService = new RestMessageHeaderService($this->loginCredentials);
		
		$message = $sentMessageService->message($id);

		return $message->status;
		
	}
	
}