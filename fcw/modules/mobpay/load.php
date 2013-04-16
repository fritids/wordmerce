<?php

class mobpay{

	private $login, $password, $brandname, $client, $args;
	
	function mobpay(){
		
		$this->__construct($args);
		
	}
	
	function __construct($args){
		
		global $config;
		
		$this->login = $args['login'];
		
		$this->password = $args['password'];
		
		$this->brandname = $args['BrandName'];
				
		ini_set("soap.wsdl_cache_enabled", "0");

		require_once("MobpayService.php");
		
		$this->client = $this->client();
		
	}
	
	function client(){
		
		$client = new MobpayService("http://browseandbuy.dialogue.net/mobpay-services/mobpay/mobpay.wsdl",
			array(
				'login' => $this->login,
				'password' => $this->password
			)  		
		);
		
		return $client;
		
	}
	
	function one_off_purchase($args){
	
		extract($args);
		
		$classification = new ClassificationType();
		$classification->Category = $Category;
		$classification->Adult = $Adult;
		$moneyType = new MoneyType();
		$moneyType->CurrencyCode = $CurrencyCode;
		$moneyType->MonetaryValue = $MonetaryValue;
		$params = new InitiateOneOffPurchase();
		$params->BrandName = $this->brandname;
		$params->ProductCode = $ProductCode;
		$params->ProductDescription = $ProductDescription;
		$params->Classification = $classification;
		$params->Cost = $moneyType;
		$params->ReturnUrl = $ReturnUrl;
		$params->FulfilmentUrl = $FulfilmentUrl;
		$params->ReceiptSms = $ReceiptSms;
		$params->ServiceDeliveryMessage = $ServiceDeliveryMessage;
		    
		$response = $this->client->InitiateOneOffPurchase($params);
		
		return $response;
		
	}
	
	function get_status($trans){
		
		$params = new GetOneOffPurchasePaymentStatus();
		
		$params->PaymentId = $trans;

		$response = $this->client->GetOneOffPurchasePaymentStatus($params);

		return $response->PaymentStatus;
		
	}
	
}