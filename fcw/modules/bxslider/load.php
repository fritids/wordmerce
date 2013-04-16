<?php

class bxslider{

	private $args;
	
	function bxslider($args){
		
		$this->__construct();
		
	}
	
	function __construct($args){
		
		$this->args = $args;
		
		$this->add_scripts();
		
	}
	
	function add_scripts(){
	
		global $paths;
		
		$elements = array();
				
		$images_string = array();
		
		$slider_options = array();
		
		$i = 0;
		
		foreach($this->args as $arg){
		
			$elements[$arg['element']]['images'] = '';
			
			if(isset($arg['images'])){
			
				foreach($arg['images'] as $image){
					$elements[$arg['element']]['images'] .= '"'.$image.'",';
				}
			
				$elements[$arg['element']]['images'] = substr_replace($elements[$arg['element']]['images'] ,"",-1);
			
			}
			
			$elements[$arg['element']]['options'] = '';
			
			foreach($arg['options'] as $option => $v){
				$elements[$arg['element']]['options'] .= $option.':\''.$v.'\', ';
			}
			
			$elements[$arg['element']]['options'] = substr_replace($elements[$arg['element']]['options'] ,"",-2);
		
			$elements[$arg['element']]['options'] = '{'.$elements[$arg['element']]['options'].'}';
			
			$i++;
		
		}
								
		echo "
			<script>
				(function() {
					function load_jquery(){
						var r = false;
				    	var s = document.createElement('script');
				        s.type = 'text/javascript';
				        s.async = true;
				        s.id = 'script_jquery_bxslider';
				        s.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
				        var x = document.getElementsByTagName('script')[0];
				        x.parentNode.insertBefore(s, x);
				        s.onload = s.onreadystatechange = function(){
				        	if(!r && (!this.readyState || this.readyState == 'complete')){
				        		r = true;
				        		//console.log('jquery loaded');
				        		load_bxslider();
				        	}
				        }
					}
					function load_bxslider(){
						r = true;
		        		rr = false;
		        		var s = document.createElement('script');
				        s.type = 'text/javascript';
				        s.async = true;
				        s.id = 'script_bxslider_bxslider';
				        s.src = '".$paths['url'] .  $paths['fcw_path']."/modules/bxslider/js/jquery.bxslider.min.js';
				        var x = document.getElementsByTagName('script')[0];
				        x.parentNode.insertBefore(s, x);
				        s.onload = s.onreadystatechange = function(){
				        	if(!rr && (!this.readyState || this.readyState == 'complete')){
				        		rr = false;
				        		//console.log('bxslider loaded');
				        		load_setup();
				        	}
				        }
					}
					function load_setup(){
						rr = true;
		        		rrr = false;
		        		var s = document.createElement('script');
				        s.type = 'text/javascript';
				        s.async = true;
				        s.id = 'script_setup__bxslider';
				        s.src = '".$paths['url'] .  $paths['fcw_path']."/modules/bxslider/js/setup.js';
				        var x = document.getElementsByTagName('script')[0];
				        x.parentNode.insertBefore(s, x);
				        s.onload = s.onreadystatechange = function(){
				        	if(!rrr && (!this.readyState || this.readyState == 'complete')){
				        		rrr = true;
				        		//console.log('setup loaded');	
				        	}
				        }

					}
				    function async_load(){
				    	load_jquery();
				        var s = document.createElement('link');
				        s.type = 'text/css';
				        s.async = true;
				        s.rel = 'stylesheet';
				        s.href = '".$paths['url'] .  $paths['fcw_path']."/modules/bxslider/css/jquery.bxslider.css';
				        var x = document.getElementsByTagName('link')[0];
				        x.parentNode.insertBefore(s, x);
				    }
				    if (window.attachEvent)
				        window.attachEvent('onload', async_load);
				    else
				        window.addEventListener('load', async_load, false);
				})();
				
				var elements = {";
				
				$i = 0;
				
				foreach($elements as $k => $element){
				
					if($i == 0){
						echo "'".$k."':{";
					}else{
						echo ", '".$k."':{";
					}
						echo "'images':[";
							echo $element['images'];
						echo "],";
						echo "'options':".$element['options']; 
					echo "}";
					
					$i++;
				
				}			
			
				echo "};
			
			</script>
		";
		
	}

}