<?php

class cronjob{

	private $args;

	function cronjob($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
		
		if(isset($args['time'])){
		
			$time = explode(':', $args['time']);

			$args['time'] = gmmktime($time[0], $time[1], 0, date("m"), date("d"), date("Y"));
		
		}else{
		
			$args['time'] = time();
			
		}
	
		$this->args = $args;
		
		extract($this->args);

		add_action('init', array( &$this, 'activate_cron' ));
		
		add_action($name, $function);
	
	}
	
	function activate_cron(){

		extract($this->args);

		if ( !wp_next_scheduled( $name ) ) {
			
			wp_schedule_event( $time, $interval, $name);
		
		}
	
	}
	
	function clear(){
	
		add_action('init', array( &$this, 'deactivate_cron' ));
	
	}
	
	function deactivate_cron(){
	
		extract($this->args);
	
		wp_clear_scheduled_hook($name) ;
	
	}

}

class cronschedules{

	private $args;

	function cronschedules($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
	
		$this->args = $args;
		
		add_filter( 'cron_schedules', array( &$this, 'add_schedules') ); 
	
	}
	
	function add_schedules($schedules){
	
		foreach($this->args as $sch){
	
			$schedules[$sch['name']] = array(
				'interval' => $sch['interval'],
				'display' => __($sch['display'])
			);
		
		}
		
		return $schedules;
	
	}

}