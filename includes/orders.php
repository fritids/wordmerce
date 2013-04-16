<?php

class orders{

	public function orders(){
		
		$this->__construct();
		
	}
	
	public function __construct(){
	
	
	
	}
	
	function get_order($id){
		
		return get_post($id);
		
	}
	
	function get_name($id){
		
		return get_the_title($id);
		
	}
	
	function get_order_data($id){
		
		return get_post_meta($id, 'order_data', true);
		
	}
	
	function add_order($id, $data, $status, $link_to = ''){
	
		$customer = new customers;
		
		if($customer->is_logged_in()){
	
			$item = get_post($id);
			
			$order_args = array(
			  'post_status'    => 'publish',
			  'post_title'     => $item->post_title,
			  'post_type'      => 'orders',
			);  
			
			$order_id = wp_insert_post( $order_args );
			
			add_post_meta($order_id, 'order_data', $data);
			
			add_post_meta($order_id, 'status', $status);
			
			if($link_to != '' && $order_id){
				
				add_post_meta($order_id, 'link_to', $link_to);
				
			}
			
			return $order_id;
		
		}else{
			
			return false;
			
		}
		
	}
	
	function update_status($id, $status){
		
		if(update_post_meta($id, 'status', $status)){
			return true;
		}else{
			return false;
		}
		
	}
	
	function add_gateway_transaction_id($id, $tran){
		
		if(update_post_meta($id, 'gateway_transaction_id', $tran)){
			return true;
		}else{
			return false;
		}
		
	}
	
	function set_gateway($id, $gateway){
		
		if(update_post_meta($id, 'gateway', $gateway)){
			return true;
		}else{
			return false;
		}
		
	}
	
	function get_order_product($id){
		
		$order = get_post($id);
		
		return $order->post_title;
		
	}
	
	function get_order_status_class($id){
		
		$status = $this->get_order_status($id, true);
		
		switch($status){
		
			case '3':
			
				return 'success';
			
			break;
			
			case '10': case '999': case '0':
			
				return 'error';
			
			break;
			
			case '2.3': case '2.5':
			
				return 'warning';
			
			break;
			
			default:
			
				return 'info';
			
			break;
		
		}
		
	}
	
	function get_order_status($id, $key=false){
				
		$status = get_post_meta($id, 'status', true);

		switch($status){
		
			case '0':
			
				return ($key ? '0' : 'Cancelled');
			
			break;
			
			case '1':
			
				return ($key ? '1' : 'In Basket');
			
			break;
			
			case '1.5':
			
				return ($key ? '1.5' :'In Checkout');
			
			break;
			
			case '2':
			
				return ($key ? '2' : 'Ordered');
			
			break;
			
			case '2.3':
			
				return ($key ? '2.3' : 'Payment pending');
			
			break;
			
			case '2.5':
			
				return ($key ? '2.5' : 'Payment in progress');
			
			break;
			
			case '3':
			
				return ($key ? '3' : 'Complete');
			
			break;
			
			case '10':
			
				return ($key ? '10' :' Payment failed');
			
			break;
			
			case '999':
			
				return ($key ? '999' : 'Error');
			
			break;
			
			default:
			
				return ($key ? '-1' : '!! Order Status not recognised !!');
			
			break;
			
		}
		
	}
	
	function add_data_to_order($id, $key, $value){
		
		add_post_meta($id, $key, $value);
		
	}
	
	function get_data($id, $key){
		
		return get_post_meta($id, $key, true);
		
	}
	
	function get_customer_id($id){
		
		return get_post_meta($id, 'link_to', true);
		
	}
	
	function get_customer_obj($id){
		
		return get_post(get_post_meta($id, 'link_to', true));
		
	}
	
	function verify_purchase($id){
		
		$status = $this->get_order_status($id, true);

		if($status == 3){
			return true;
		}else{
			return false;
		}
		
	}
	
	function send_purchase_receipt($id){
	
		$header_image = wp_get_attachment_image_src( get_field('receipt_header_image', 'option'), 'full' );
	
		$replace = array(
			'%%TITLE%%' => get_field('receipt_title', 'option'),
			'%%HEADER_IMAGE%%' => $header_image[0],
			'%%HEADING%%' => get_field('receipt_heading', 'option'),
			'%%CONTENT%%' => get_field('receipt_content', 'option'),
			'%%COPYRIGHT%%' => get_field('receipt_copyright', 'option'),
			'%%FOOTER%%' => get_field('receipt_footer', 'option')
		);
		
		$email = file_get_contents(dirname(__FILE__).'/views/email.php');
		
		foreach($replace as $k => $v){
			
			$email = str_replace($k, $v, $email);
			
		}
		
		$c_id = $this->get_customer_id($id);
		
		$customer = new customers;
		
		$email = apply_filters('email_tags_replace', $email, $c_id, $id);

		$email_address = 'andycharrington@gmail.com';
		
		$email_address = $customer->get_data($c_id, 'email');
				
		$headers = 'From: '.get_field('from_name', 'option').' <'.get_field('from_email', 'option').'>' . "\r\n";
		
		$e_s = $this->get_data($id, 'emails_sent');
		
		$emails_sent = is_array($e_s) ? $e_s : array();
		
		if(count($emails_sent) == 0){
		
			if(wp_mail( $email_address, get_field('receipt_title', 'option'), $email, $headers )){
							
				array_push($emails_sent, date('l jS \of F Y h:i:s A'));
				
				$this->add_data_to_order($id, 'emails_sent', $emails_sent);
				
				echo '<p>Email sent to '.$email_address.'.</p>';
				
			}else{
				
				echo '<p>Sorry but there was a problem sending your email receipt. Please refresh this page to try again</p>';
				
			}
		
		}else{
		
			$emails_sent = array_reverse($emails_sent);
			
			echo 'Your email receipt was last sent to ' . $email_address . ' on ' . $emails_sent[0];
			
		}
		
	}
	
}

function order_product_name($id){

	$orders = new orders;
	
	$link = get_edit_post_link( $id );
	
	echo '<a href="'.$link.'">' . $orders->get_order_product($id) . '</a>';
	
}

function order_status($id){

	$order = new orders;
	
	$status = $order->get_order_status($id);
	
	$status_class = $order->get_order_status_class($id);
					
	echo '<span class="label label-'.$status_class.'">'.$status.'</span></p>';
	
}

function customer_column($id){

	$order = new orders;
	
	$customer = new customers;
	
	$c_id = $order->get_customer_id($id);
	
	$link = get_edit_post_link( $c_id );
	
	echo '<a target="_blank" href="'.$link.'">' . $customer->get_name($c_id) . '</a>';
	
}