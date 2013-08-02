<?php

$store_pickup = new store_pickup;

class store_pickup{

	function store_pickup(){
		
		$this->__construct();
		
	}
	
	function __construct(){
	
		add_action('wp_head', array(&$this, 'add_script'));
		
		add_filter('wordmerce_shipping_total', array(&$this, 'show_pickup_option'), 99999999, 1);
		
		add_action('wp_ajax_store_pickup', array(&$this, 'ajax_update' ));

		add_action('wp_ajax_nopriv_store_pickup', array(&$this, 'ajax_update' ));	
		
	}
	
	function add_script(){
					
		wp_enqueue_script( 'store_pickup_script', plugins_url( 'js/script.js', __FILE__ ));
				
	}
	
	function show_pickup_option(){
		
		$return = '<p>Prefer to pick up in store?</p>';
		
		$i=0;
		
		$stores = get_option('options_store_locations');
		
		$return .= '<select class="store_pickup">';
		
			$return .= '<option value="">Choose your nearest store</option>';
		
			while($i < $stores){
			
				$name = get_option('options_store_locations_'.$i.'_store_name');
				
				$location = get_option('options_store_locations_'.$i.'_store_location');
				
				$return .= '<option value="'.$name.'">'.$name.' - '.$location.'</option>';
				
				$i++;
				
			}
			
		$return .= '</select>';
		
		return $return;
			
	}
	
	function ajax_update(){
		
		$order = new orders;
				
		$order->update_data_on_order($_COOKIE['WM_BASKET'], 'store_pickup', $_POST['wm_location']);
				
		exit;
		
	}

}