<?php

include_once('config.php');
				
foreach($config['modules'] as $module => $v){

	if(is_array($v)){
	
		if(!class_exists($module)){

			include 'modules/'.$module . '/load.php';
		
		}
				
		foreach($v as $submodule){
		
			if(!class_exists($submodule)){
		
				include 'modules/'.$module.'/'.$submodule . '.php';
			
			}
				
		}
	
	}else{
	
		if(!class_exists($v)){
	
			include 'modules/'.$v . '/load.php';
		
		}
	
	}
	
}

$paths = array();

$paths['url'] = curPageURL();

$paths['fcw_path'] = 'fcw';

$paths['fcw_dir'] = dirname(__FILE__);

foreach($config['paths'] as $k => $v){
	
	$paths[$k] = $v;
	
}

function curPageURL() {

	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	
	$pageURL .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") {
	
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		
	} else {
	
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
	}
	
	return $pageURL;
}