<?php

$orders = new orders;

class orders{

	public function orders(){
		
		$this->__construct();
		
	}
	
	public function __construct(){
	
		add_action('wp_ajax_wm_update_order', array(&$this, 'update_order' ));

		add_action('wp_ajax_wm_nopriv_update_order', array(&$this, 'update_order' ));
		
		add_action('wp_ajax_send_purchase_receipt', array(&$this, 'send_purchase_receipt' ));
	
	}
	
	function get_order($id){
		
		return get_post($id);
		
	}
	
	function get_name($id){
		
		return get_the_title($id);
		
	}
	
	function update_name($id, $title){
		
		$order = array();
		$order['ID'] = $id;
		$order['post_title'] = $title;

		wp_update_post( $order );
		
	}
	
	function get_order_data($id){
		
		return get_post_meta($id, 'order_data', true);
		
	}
	
	function update_order(){
	
		$nonce = $_POST['wordmerce_nonce'];
		
		if ( ! wp_verify_nonce( $nonce, 'wordmerce_nonce' ) )
			die ( 'Busted!');
									
		$customer = new customers;
		
		$u_id = $customer->is_logged_in();
		
		//print_r($_COOKIE); exit;

		if(isset($_POST['basket_id']) && strlen($_POST['basket_id']) > 0){
				
			$id = $_POST['basket_id'];
			
		}else{

			$id = $this->add_order($_POST['title'], (isset($_POST['data']) ? $_POST['data'] : ''), $_POST['status'], ($u_id ? $u_id : ''));

				//print_r($_COOKIE); exit;
			echo 'refresh';
			
			exit;
				
		}
		
		$products = get_post_meta($id, 'products', true);
		
		$basket_quantities = array();
		
		if(is_array($products)){
					
			foreach($products as $product => $data){
			
				$basket_quantities[$data['id']] +=  $data['quantity'];
			
			}
			
		}
			
		if(isset($_POST['products'])){
		
			$flag = false;
			
			foreach($_POST['products'] as $product => $data){
			
				$p_id = $data['id']; 
				
				$quantity = ($data['quantity'] - $basket_quantities[$p_id]);
				
				$current_stock = get_post_meta($p_id, 'stock', true);
echo $current_stock.' - '.$quantity;
				if($quantity > $current_stock){
					
					$flag = $p_id;
					
					break;
					
				}
				
			}
			
		}
		
		if(isset($flag) && $flag){
			
			echo 'over_stock||'.$flag;
			
			exit;
			
		}
							
		$this->update_data_on_order($id, 'products', (isset($_POST['products']) ? $_POST['products'] : ''));
		
		$this->update_data_on_order($id, 'total', $_POST['total']);
		
		if($u_id){ $this->update_data_on_order($id, 'link_to', $u_id); };
		
		$this->update_name($id, $_POST['title']);
		
		$this->update_status($id, $_POST['status']);
		
		if($id != ''){ setcookie("WM_BASKET", $id, time()+3600*24, '/', false); };
		
		do_action('wordmerce/basket/update', $id);
				
		echo $id;
		
		exit;
		
	}
	
	function add_order($id, $data, $status, $link_to = ''){

		$customer = new customers;
		
		//if($customer->is_logged_in()){
		
			if(is_int($id)){
	
				$item = get_post($id);
				
				$title = $item->post_title;
				
			}else{
				
				$title = $id;
				
			}
			
			$order_args = array(
			  'post_status'    => 'publish',
			  'post_title'     => $title,
			  'post_type'      => 'orders',
			);  
			
			$order_id = wp_insert_post( $order_args );

			add_post_meta($order_id, 'order_data', $data);
			
			add_post_meta($order_id, 'status', $status);
			
			if($link_to != '' && $order_id){

				add_post_meta($order_id, 'link_to', $link_to);
				
			}

			if($order_id != ''){ $cookie = setcookie("WM_BASKET", $order_id, time()+3600, '/', false); };
	
			//update_option('basket_created_', 'done');
			
			return $order_id;
		
		/*
}else{
			
			return false;
			
		}
		
*/
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
	
	function update_data_on_order($id, $key, $value){
			
		return update_post_meta($id, $key, $value);
		
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
	
	function send_purchase_receipt($id=''){
	
		$manual = false;
	
		if($id == ''){
		
			if(isset($_POST['order_id'])){
		
				$id = $_POST['order_id'];
				
				$manual = true;
			
			}else{
				
				return 'No ID sent...';
				
			}
		}
	
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
		
		//$email_address = $customer->get_data($c_id, 'email');
				
		$headers = 'From: '.get_field('from_name', 'option').' <'.get_field('from_email', 'option').'>' . "\r\n";
		
		$e_s = $this->get_data($id, 'emails_sent');
		
		$emails_sent = is_array($e_s) ? $e_s : array();
		
		if(count($emails_sent) == 0 || $manual){
		
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
		
		if($manual){
		
			die();
			
		}
		
	}
	
	function render_cart(){
	
		$customers = new customers;
		
		$is_logged_in = $customers->is_logged_in();
		
		$return = '<div class="navbar" id="checkout_progress">
		  <div class="navbar-inner">
		    <a class="brand" href="#">Checkout Progress</a>
		    <ul class="nav">
		      <li class="active"><a data-targets="#checkout_basket" href="#"><i class="checkout_status icon-ok"></i>Basket</a></li>
		      <li><a data-targets="#checkout_login" href="#">';
		      
		      if($customers->is_logged_in()){
		      
				  $return .= '<i class="checkout_status icon-ok"></i>';
		      
		      }else{
			      
				  $return .= '<i class="checkout_status icon-remove"></i>';
			      
		      }
		      
		      $return .= 'Account</a></li>';
		      if(SHIPPING){
		      	$return .= '<li><a data-targets="#checkout_shipping" href="#"><i class="checkout_status icon-remove"></i>Shipping</a></li>';
		      }
		      $return .= '<li><a data-targets="#checkout_payment" href="#"><i class="checkout_status icon-remove"></i>Payment</a></li>
		    </ul>
		  </div>
		</div>';
		
		$return .= '<div id="checkout_stuff">
		
			<div class="login_alert alert alert-error hide">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<span id="error_message"></span>
			</div>';
		
			$return .= '<div class="simpleCart_items collapses" id="checkout_basket"></div>';
			
			$return .= '<div class="page-header collapses" id="checkout_login">';
			
				if($is_logged_in){
					
					$return .= '<h2>Logged in as '. $customers->get_name($is_logged_in) .'</h2>
					<a href="#" id="logout_link">Log Out?</a>
					
					<input type="hidden" id="user_id" value="'.$is_logged_in.'" />';
					
				}else{
					
					$return .= '<div class="page-header"><button class="btn" id="checkout_login">Log In or Register</button></div>
					
					<input type="hidden" id="user_id" value="" />';
					
				}
	
			$return .= '</div>';
			
			if(SHIPPING){
			
				$return .= '<div id="checkout_shipping" class="collapses">
					
					<div class="page-header">
					
						<div id="customer_address" class="form-horizontal">
							
						  <div class="control-group">
						  	<label class="control-label" for="inputAddress1">Address Line 1</label>
						    	<div class="controls">
									'. ($customers->get_data($is_logged_in, 'inputAddress1') ? '<i class="icon-ok-sign"></i>' : '<i class="icon-remove-sign"></i>') .'<input type="text" id="inputAddress1" placeholder="" value="'.$customers->get_data($is_logged_in, 'inputAddress1').'">
								</div>
						  </div>
						  
						  <div class="control-group">
						  	<label class="control-label" for="inputAddress2">Address Line 2</label>
						    	<div class="controls">
									'. ($customers->get_data($is_logged_in, 'inputAddress2') ? '<i class="icon-ok-sign"></i>' : '<i class="icon-remove-sign"></i>') .'<input type="text" id="inputAddress2" placeholder="" value="'.$customers->get_data($is_logged_in, 'inputAddress2').'">
								</div>
						  </div>
						  
						  <div class="control-group">
						  	<label class="control-label" for="inputTownCity">Town/City</label>
						    	<div class="controls">
									'. ($customers->get_data($is_logged_in, 'inputTownCity') ? '<i class="icon-ok-sign"></i>' : '<i class="icon-remove-sign"></i>') .'<input type="text" id="inputTownCity" placeholder="" value="'.$customers->get_data($is_logged_in, 'inputTownCity').'">
								</div>
						  </div>
						  
						  <div class="control-group">
						  	<label class="control-label" for="inputRegionCounty">Region/County/State</label>
						    	<div class="controls">
									'. ($customers->get_data($is_logged_in, 'inputRegion') ? '<i class="icon-ok-sign"></i>' : '<i class="icon-remove-sign"></i>') .'<input type="text" id="inputRegion" placeholder="" value="'.$customers->get_data($is_logged_in, 'inputRegion').'">
								</div>
						  </div>
						  
						  <div class="control-group">
						  	<label class="control-label" for="inputPostcode">Postcode/Zipcode</label>
						    	<div class="controls">
									'. ($customers->get_data($is_logged_in, 'inputPostcode') ? '<i class="icon-ok-sign"></i>' : '<i class="icon-remove-sign"></i>') .'<input type="text" id="inputPostcode" placeholder="" value="'.$customers->get_data($is_logged_in, 'inputPostcode').'">
								</div>
						  </div>
						  
						   <div class="control-group">
						  	<label class="control-label" for="inputCountry">Country</label>
						  		<div class="controls">
						  
						  			'. ($customers->get_data($is_logged_in, 'inputCountry') ? '<i class="pull-left icon-ok-sign"></i>' : '<i class="pull-left icon-remove-sign"></i>') .'<select id="inputCountry" name="country" class="pull-left">
				
									  	<option value="">--- Select ---</option>';
					
										$countries = tpc_countries();
										
										foreach($countries as $cc => $c_name){
										
											$selected = ($cc == $customers->get_data($is_logged_in, 'inputCountry') ? ' selected="selected" ' : "");
										
											$return .= '<option '. $selected .' value="' . $cc . '">' . $c_name . '</option>';
										
										}
				
									$return .= '</select>
									
									<div id="countries_spin" class="spin"></div>
									
								</div>
						  </div>
										
						</div>
						
					</div>
				
				</div>';
			
			}
			
			$return .= '<div id="checkout_payment" class="collapses">';
			
				$return .= apply_filters('wordmerce_payment_form', '1');
			
			$return .= '</div>
			
			<div class="page-header" id="">
					
				<h2>Sub-total:  <span class="simpleCart_total"></span></h2>
					
			</div>';
			
			if(SHIPPING){
				
				$return .= '<div class="page-header">
					<h2>
						Shipping: 
						<span class="simpleCart_shipping"></span>
					</h2>
				</div>';
				
			}
					
			$return .= '<div class="page-header">
			
				<h1>Total:  <span class="simpleCart_grandTotal"></span></h1>
			
			</div>
			
			<a href="javascript:;" class="pull-left simpleCart_empty btn btn-warning btn-small"><i class="icon-white icon-remove"></i> Empty Basket</a>
	
			<a href="javascript:;" id="checkout_now_button" class="disabled pull-right btn btn-success btn-large"><i class="icon-white icon-ok"></i> Checkout Now</a>';
			
			//$return .= '<a href="#" class="btn btn-success btn-large">Delivery Details <i class="icon-white icon-chevron-down"></i></a>';

		$return .= '</div>';
		
		echo $return;
		
	}
	
	function does_exist($id){
	   
	    if($id != ''){

		    $args = array(
			   'numberposts' => 1,
			   'post_type' => 'orders',
			   'post_status' => array( 'publish', 'pending', 'draft'),
			   'meta_query' => array(
			       array(
			           'key' => 'id',
			           'value' => $id,
			           'compare' => '=',
			       )
			   )
			);
			$order = get_posts ( $args );
	
			if(count($order) > 0){
				
				return $order[0];
				
			}else{
				
				return false;
				
			}
		
		}else{
			
			return false;
			
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
	
	if($c_id != ''){
		
		$link = get_edit_post_link( $c_id );
		
		echo '<a target="_blank" href="'.$link.'">' . $customer->get_name($c_id) . '</a>';
	
	}else{
		
		echo '';
		
	}
}