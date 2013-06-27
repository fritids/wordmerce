<?php

$paymill = new paymill;

class paymill{
	
	function paymill(){
		
		$this->__construct();
		
	}
	
	function __construct(){
				
		add_action('wp_head', array(&$this, 'add_script'));
		
		add_filter('wordmerce_payment_form', array(&$this, 'payment_form'), 99999999, 1);
		
		//add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
		
		add_action('wp_ajax_process_payment', array(&$this, 'process_payment' ));

		add_action('wp_ajax_nopriv_process_payment', array(&$this, 'process_payment' ));
		
	}
	
	function add_script(){
	
		$paymill_public_key = get_field('PAYMILL_PUBLIC_KEY', 'options');
		
		wp_enqueue_script( 'paymill', 'https://bridge.paymill.com/' );
		
		wp_enqueue_script( 'paymill_script', plugins_url( 'js/script.js', __FILE__ ));
		
		echo '<script type="text/javascript">var PAYMILL_PUBLIC_KEY = \''.$paymill_public_key.'\';</script>';
		
	}
	
	function payment_form($return){
		
		$return = '<form id="payment-form" action="#" method="POST">
		
		  <input class="card-amount-int" type="hidden" value="15" />
		  <input class="card-currency" type="hidden" value="GBP" />
		
		  <div class="control-group form-row"><label>Card number</label>
		    <input class="card-number" data-validate="validateCardNumber" type="text" size="20" /></div>
		
		  <div class="control-group form-row"><label>CVC</label>
		    <input data-validate="validateCvc" class="card-cvc" type="text" size="4" /></div>
		
		  <div class="control-group form-row"><label>Name</label>
		    <input class="card-holdername" data-validate="none" type="text" size="4" /></div>
		
		  <div class="control-group form-row"><label>Expiry date (MM/YYYY)</label>
		    <input class="card-expiry-month" data-validate="validateExpiry" type="text" size="2" />
		    <span></span>
		    <input class="card-expiry-year" data-validate="validateExpiry" type="text" size="4" /></div>
				
		</form>';
		
		return $return;
		
	}
	
	function process_payment(){

		if ( ! wp_verify_nonce( $_POST['wordmerce_nonce'], 'wordmerce_nonce' ) )
			die ( 'Busted!');

		require_once dirname( __FILE__ ) . '/Paymill/Transactions.php';

		$paymill_private_key = get_field('PAYMILL_PRIVATE_KEY', 'options');

		$params = array(
			'amount'      => $_POST['amount'],
			'currency'    => $_POST['currency'],
			'token'       => $_POST['token'],
			'description' => $_POST['description']
		);
		$apiKey             = $paymill_private_key;
		$apiEndpoint        = 'https://api.paymill.com/v2/';
		$transactionsObject = new Services_Paymill_Transactions($apiKey, $apiEndpoint);
		$transaction        = $transactionsObject->create($params);
		
		$status = $this->translate_codes($transaction['response_code']);
		
		$order = new orders;
		
		$id = $order->get_name($_COOKIE['WM_BASKET']);
		
		$data = $transaction;
				
		//update_post_meta($product_id, 'sales', $count++);
		
		$order->add_data_to_order($_COOKIE['WM_BASKET'], 'payment_id', $transaction['id']);
		
		$order->add_data_to_order($_COOKIE['WM_BASKET'], 'gateway_transaction_id', $transaction['id']);

		$order->add_data_to_order($_COOKIE['WM_BASKET'], 'payment_info', $data);
		
		$order->add_data_to_order($_COOKIE['WM_BASKET'], 'gateway', 'PayMill');
					
		$order_id = $order->update_status($_COOKIE['WM_BASKET'], '3');
				
		//if($order_id){
			
			//setcookie( 'WM_BASKET');
			
			$b_id = $_COOKIE['WM_BASKET'];
			
			setcookie('WM_BASKET', '', time()-1, '/', $_SERVER['HTTP_HOST']);
			
			echo $b_id;
			
		//}else{
			
			//return 'nope';
			
		//}
		
		exit;

	}
	
	function translate_codes($code){
		
		switch($code){
			
			case 20000:
			
				return 3;
				
			break;
			
			case 40000:
			case 40100:
			case 40101:
			case 40102:
			case 40103:
			case 40104:
			case 40105:
			case 40200:
			case 40300:
			case 40301:
			case 40400:
			case 40401:
			case 40402:
			case 40403:
			case 50000:
			case 50001:
			case 50100:
			case 50101:
			case 50102:
			case 50103:
			case 50104:
			case 50105:
			case 50200:
			case 50201:
			case 50300:
			case 50400:
			case 50500:
			case 50501:
			
				return 10;
				
			break;
			
			default:
			
				return -1;
				
			break;
			
		}
		
	}

}