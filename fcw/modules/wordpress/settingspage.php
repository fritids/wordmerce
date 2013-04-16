<?php

class settingspage{

	private $args;
	
	function settingspage($args){
		
		$this->__construct($args);
		
	}
	
	function __construct($args){
	
		$this->args = $args;
		
		add_filter('acf_settings', array(&$this, 'my_acf_settings' ));
		
		if ( ! class_exists( 'acf_lite' ) )
			require_once('acf/acf-lite.php');
					
	}
	
	function my_acf_settings( $options ){
	
		extract($this->args);
	
		global $config;
	    // activate add-ons
	    $options['activation_codes']['options_page'] = (isset($config['acf-activation-codes']['options_page']) ? $config['acf-activation-codes']['options_page'] : '');
	    
	    // set options page structure
	    $options['options_page']['title'] = $name;
	    $options['options_page']['pages'] = $pages;
	    
	        
	    return $options;
    
    }
    
    function get_slug(){
    
    	extract($this->args);
	    
	    if(count($pages) == 1){
		    return 'acf-options-default-settings';
	    }
	    
    }
	
}