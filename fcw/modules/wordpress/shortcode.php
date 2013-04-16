<?php

class shortcode{
	
	function shortcode($args){
		
		$this->__construct($args);
		
	}
	
	function __construct($args){
	
		add_shortcode( $args['shortcode'], $args['function'] );
	
	}
	
}