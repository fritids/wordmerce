<?php

$gateway_dialogue = new gateway_dialogue('');

class gateway_dialogue{

	private $args;
	
	private $status_codes = array(
		'00' => 'SUCCESSFUL',
		'22' => 'REQUESTED',
		'30' => 'PENDING',
		'72' => 'INSUFFICIENT_CREDIT',
		'73' => 'REGULATORY_SPENDING_LIMIT_REACHED',
		'74' => 'NOT SUPPORTED RESERVED N/A FAILURE_OTHER'
	);
	
	function gateway_dialogue($args){
		
		$this->__construct($args);
		
	}
	
	function __construct($args){
		
		$this->args = $args;
		
		add_action( 'wordmerce/notify', array(&$this, 'get_notification'), 10, 1  );
		
	}
	
	function recieve_notification(){
		
		
		
	}
	
	function one_off_purchase($args){
		
		$mobpay = new mobpay($this->args);
			
		return $mobpay->one_off_purchase($args);
		
	}
	
	function get_notification($args){
			
		/*
		
		Possible status codes:
		
		StatusCode									MisCode		Description
		
		SUCCESSFUL									00 			Payment successful 
		REQUESTED 									22 			Payment requested to Mobile Payments but not yet processed 
		PENDING 									30 			Processing/Pending confirmation 
		INSUFFICIENT_CREDIT 						72 			Insufficient pre-pay credit 
		REGULATORY_SPENDING_LIMIT_REACHED 			73 			Regulatory spending limit reached 
		NOT_SUPPORTED RESERVED N/A FAILURE_OTHER 	74			
		
		*/
			
		$find = array(
			'post_type' => 'orders',
			'meta_query' => array(
				array(
					'key' => 'gateway_transaction_id',
					'value' => $args['paymentId'],
				)
			)
		 );
		
		$order_obj = get_posts($find);
		
		print_r($order_obj);
		
		if(count($order_obj) > 0){
		
			$order_id = $order_obj[0]->ID;
			
			$order = new orders;
			
			$order->add_data_to_order($order_id, 'payment_id', $args['transactionId']);
			
			$order->add_data_to_order($order_id, 'payment_info', $args);
			
			//$order->add_gateway_transaction_id($order_id, $args['transactionId']);
			
			switch($args['status']){
			
				case '00': 
									
					$product_id = $order->get_data($order_id, 'product_id');
					
					$count = get_post_meta($product_id, 'sales');
					
					update_post_meta($product_id, 'sales', $count++);
					
					$order->update_status($order_id, '3');
					
				break;
				
				case '22':
				
					$order->update_status($order_id, '2.3');
					
					$order->add_data_to_order($order_id, 'gateway_error', $this->status_codes['22']);
				
				break;
				
				case '30':
				
					$order->update_status($order_id, '2.3');
					
					$order->add_data_to_order($order_id, 'gateway_error', $this->status_codes['30']);
				
				break;
				
				case '72':
				
					$order->update_status($order_id, '10');
					
					$order->add_data_to_order($order_id, 'gateway_error', $this->status_codes['72']);
				
				break;
				
				case '73':
				
					$order->update_status($order_id, '10');
					
					$order->add_data_to_order($order_id, 'gateway_error', $this->status_codes['73']);
				
				break;
				
				case '74':
				
					$order->update_status($order_id, '10');
					
					$order->add_data_to_order($order_id, 'gateway_error', $this->status_codes['74']);
				
				break;
				
				default:
				
					$order->update_status($order_id, '10');
					
					$order->add_data_to_order($order_id, 'gateway_error', 'Unkown error');
				
				break;
			
			}
		
		}
		
		wp_mail('andycharrington@gmail.com', 'Gateway Notification', print_r($args, true));
		
	}
	
}