<?php

class wordpress{
	
	private $actions = array();

	private $cpts = array();

	function wordpress(){
	
		$this->__construct();
	
	}
	
	function __construct(){
	
		add_action('init', array( &$this, 'do_everything' ));
	
	}
	
	function do_everything(){

		$this->do_actions();
	
	}
	
	function do_actions(){
	
		foreach($this->actions as $v){

			add_action( $v['hook'], array( &$this, $v['function'] ) );
		
		}
	
	}

}

?>