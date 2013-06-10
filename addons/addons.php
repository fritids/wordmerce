<?php

class addons{

	public $addons = array();

	public function addons(){
		
		$this->__construct();
		
	}
	
	public function __construct(){
	
		$this->load_addons();
			
	}

	function load_addons(){
					
		foreach(glob(dirname( __FILE__ ).'/*/load.php') as $plugin){
		
			$default_headers = array(
				'Name' => 'Name',
				'Description' => 'Description',
				'Dependencies' => 'Dependencies'
			);
			
			$a = get_file_data($plugin, $default_headers);
			
			array_push($this->addons, $a);
			
			if(get_field(strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $a['Name']), '-')), 'options')){
				
				include_once($plugin);
				
			}
		
		}
		
	}
	
}