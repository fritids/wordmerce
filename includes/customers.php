<?php

$customers = new customers;

class customers{

	public function customers(){
		
		$this->__construct();
		
	}
	
	public function __construct(){
	
		add_action('wp_ajax_is_logged_in', array(&$this, 'is_logged_in_ajax' ));

		add_action('wp_ajax_nopriv_is_logged_in', array(&$this, 'is_logged_in_ajax' ));
		
		add_action('wp_ajax_WM_log_in', array(&$this, 'log_user_in' ), 10, 1);

		add_action('wp_ajax_nopriv_WM_log_in', array(&$this, 'log_user_in' ), 10, 1);
		
		add_action('wp_ajax_log_out', array(&$this, 'log_out' ));

		add_action('wp_ajax_nopriv_log_out', array(&$this, 'log_out' ));
		
		add_action('wp_ajax_signin_login', array(&$this, 'signin_login' ));

		add_action('wp_ajax_nopriv_signin_login', array(&$this, 'signin_login' ));
	
	}
	
	function get_name($id){
		
		return get_the_title($id);
		
	}
	
	function get_customer($id){
		
		return get_post($id);
		
	}
	
	function get_data($id, $key){
		
		return get_post_meta($id, $key, true);
		
	}
	
	function add_data($id, $key, $value){
		
		return update_post_meta($id, $key, $value);
		
	}
	
	function log_out(){
		
		setcookie('WM_LOGGED_IN', '0', time()-1, '/', $_SERVER['HTTP_HOST']);
		
	}
	
	function is_logged_in_ajax(){
		
		$logged_in = $this->is_logged_in();
		
		if($logged_in){
			
			echo '1';
			
		}else{
			
			echo '0';
			
		}
		
		exit;
		
	}
	
	function is_logged_in(){

		if(isset($_COOKIE['WM_LOGGED_IN']) && $_COOKIE['WM_LOGGED_IN'] != '0'){
		
			$customer = $this->get_customer($_COOKIE['WM_LOGGED_IN']);
			
			if($customer->post_status == 'publish'){
			
				return $_COOKIE['WM_LOGGED_IN'];
			
			}else{
						
				return false;
			
			}
			
		}else{
			
			return false;
			
		}
		
	}
	
	function login_reg_form($modal=true){
		
		include_once(dirname(__FILE__).'/views/login_form.php');
		
	}
	
	function log_user_in($modal=true){
	
		echo '
			<script>
			var addthis_config = {
			        login:{
			                services:{
			                        facebook:{
			                                id:\'571657682855715\'
			                        },
			                        twitter:{
			                                id:\'hOEvrrFSu3pDU2OkAwtzA\'
			                        },
			                        google:{
			                                id:\'843981671638.apps.googleusercontent.com\'
			                        }
			                },
			                callback:function(user){
			                	jQuery(document).trigger(\'signin_login\', user);
			                }
			        }
			};
			
		';
		
		if(!$modal){
			
			echo "last_function = 'refresh_window'; last_functions_args = '';";
			
		}
		
		echo '
			</script>
			<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=andycharrington"></script>
		';
			
		echo $this->login_reg_form($modal);
		
		if($modal){
			die;
		}
		
	}
	
	function signin_login(){
		
		$nonce = $_POST['wordmerce_nonce'];
		
		if ( ! wp_verify_nonce( $nonce, 'wordmerce_nonce' ) )
			die ( 'Busted!');
			
		$user = $_POST['user']; //print_r($user); exit;
			
		$logged_in = $this->is_logged_in();
			
		if($logged_in){
			
			echo json_encode(array('user' => $logged_in));
			
		}else{
		
			$does_exist = $this->does_exist($user);
			
			if($does_exist){
				
				if(isset($user['password'])){
				
					$password = $user['password'];
					
				}elseif(isset($user['addthis_signature'])){
					
					$password = $user['addthis_signature'];
					
				}else{
					
					return json_encode(array('error' => 'Incorrect log ins'));;
					
					exit;
				}
				
				$check_password = $this->check_password($does_exist, $password);
				
				if($check_password){
				
					$this->set_logged_in($check_password);
					
					if($this->check_email($check_password, $user)){
					
						echo json_encode(array('user' => $check_password));
					
					}else{
						
						echo json_encode(array('error' => array('trigger' => 'ask_for_email', 'trigger_args' => $user, 'function' => 'signin_login')));
						
					}
					
				}else{
					
					echo json_encode(array('error' => 'Incorrect password1'));
					
				}
				
			}elseif((isset($user['new_user']) && $user['new_user'] == 'checked') || isset($user['addthis_signature'])){

				echo $this->register($user);
				
			}else{
				
				echo json_encode(array('error' => 'Incorrect log ins'));
				
			}
			
		}
		
		exit;
		
	}
	
	function check_email($id, $user){

		if($this->get_data($id, 'email') != ''){
			
			return true;
			
		}elseif(isset($user['email']) && $user['email'] != ''){
		
			if($id != ''){
				
				$this->add_data($id, 'email', $user['email']);
				
			}
		
			return true;
		
		}else{
			
			return false;
			
		}
		
	}
	
	function does_exist($user){
	    
	    $args = array(
		   'numberposts' => 1,
		   'post_type' => 'customers',
		   'post_status' => array( 'publish', 'pending', 'draft'),
		   'meta_query' => array(
		       array(
		           'key' => 'id',
		           'value' => $user['id'],
		           'compare' => '=',
		       )
		   )
		);
		$customer = get_posts ( $args );
		
		if(count($customer) > 0){
			
			return $customer[0];
			
		}else{
			
			return false;
			
		}
		
	}
	
	function check_password($customer, $password){
		
		global $wp_hasher;
		
		if ( empty($wp_hasher) ) {
	        require_once ( ABSPATH . 'wp-includes/class-phpass.php');
	        // By default, use the portable hash from phpass
	        $wp_hasher = new PasswordHash(8, TRUE);
	    }
				
		$customer = get_post($customer->ID);
		
		$hash = get_post_meta($customer->ID, '_password_hash', true);
    		    
		$check = $wp_hasher->CheckPassword($password, $hash);
    
	    if($check){
		    return $customer->ID;
	    }else{
		    return false;
	    }
		
	}
	
	function register($user){
	
		global $wp_hasher;
		
		if ( empty($wp_hasher) ) {
	        require_once ( ABSPATH . 'wp-includes/class-phpass.php');
	        // By default, use the portable hash from phpass
	        $wp_hasher = new PasswordHash(8, TRUE);
	    }
		//print_r($user); exit;
		$customer = array();
		$customer['post_type'] = 'customers';
		$customer['post_content'] = '';
		$customer['post_title'] = ($user['name'] != '' ? $user['name'] : $user['username']);
		$customer['post_status'] = 'publish';
					
		$created = wp_insert_post( $customer );
		
		if($created){
			
			update_post_meta($created, 'user', $user);
		
			update_post_meta($created, 'id', $user['id']);
		
			update_post_meta($created, 'username', $user['username']);
		
			update_post_meta($created, 'thumbnail', $user['thumbnailURL']);
			
			update_post_meta($created, 'service', $user['service']);
						
			if(isset($user[$user['service']])){
			
				update_post_meta($created, 'service_specific', $user[$user['service']]);
			
			}
			
			if(isset($user['email'])){
			
				update_post_meta($created, 'email', $user['email']);
			
			}
			
			if(isset($user['password'])){
				
				$password = $user['password'];
				
			}elseif(isset($user['addthis_signature'])){
				
				$password = $user['addthis_signature'];
				
			}else{
				
				return json_encode(array('error' => 'No password specified'));;
				
				exit;
			}
		    
		    $new_pass = $wp_hasher->HashPassword($password);
		    
		    update_post_meta($created, '_password_hash', $new_pass);
		    
		    if($this->check_email($check_password, $user)){
		    
		    	$this->set_logged_in($created);
							
				return $created;
			
			}else{
				
				return json_encode(array('error' => array('trigger' => 'ask_for_email', 'trigger_args' => $user, 'function' => 'register')));
				
				exit;
				
			}
			
		}else{
			
			return json_encode(array('error' => 'There was a problem creating your account'));;
			
		}
		
	}
	
	function set_logged_in($id){
		
		setcookie('WM_LOGGED_IN', $id, time()+62208000, '/', $_SERVER['HTTP_HOST']);
		
	}
	
	function order_belongs($order_id){
		
		$id = $this->is_logged_in();
		
		$order = new orders;
		
		$link_to = $order->get_data($order_id, 'link_to');
		
		if($id && $link_to == $id){
			
			return true;
			
		}else{
			
			return false;
			
		}
		
	}
	
	function get_orders($id){
		
		$args = array(
		   'numberposts' => -1,
		   'post_type' => 'orders',
		   'post_status' => array( 'publish', 'pending', 'draft'),
		   'meta_query' => array(
		       array(
		           'key' => 'link_to',
		           'value' => $id,
		           'compare' => '=',
		       )
		   )
		);
		$orders = get_posts ( $args );
		
		if(count($orders) > 0){
		
			return $orders;
		
		}else{
			
			return false;
			
		}
		
	}
	
	function account_page(){
	
		$id = $this->is_logged_in();
		
		if($id){
		
			$o = new orders;
		
			$customer = $this->get_customer($id);
			
			$name = $this->get_name($id);
			
			$img = $this->get_data($id, 'thumbnail');
			
			$data_src = ($img != '' ? $img : '/holder.js/100x100/social/text:'.$name);
			
			$email = $this->get_data($id, 'email');
			
			$orders = $this->get_orders($id);
		
			include_once(dirname(__FILE__).'/views/account.php');
		
		}else{
			
			$this->log_user_in(false);
			
		}
		
	}
	
}