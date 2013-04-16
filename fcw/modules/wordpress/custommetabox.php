<?php

if ( ! class_exists( 'acf_lite' ) )
	require_once('acf/acf-lite.php');
	
add_filter('acf_settings', 'my_acf_settings');

function my_acf_settings( $options ){

	global $config;
	//echo '<pre>'; print_r($config); exit;
    // activate add-ons
    $options['activation_codes']['repeater'] = (isset($config['acf-activation-codes']['repeater']) ? $config['acf-activation-codes']['repeater'] : null);
    $options['activation_codes']['flexible_content'] = (isset($config['acf-activation-codes']['flexible_content']) ? $config['acf-activation-codes']['flexible_content'] : null);
    $options['activation_codes']['gallery'] = (isset($config['acf-activation-codes']['gallery']) ? $config['acf-activation-codes']['gallery'] : null);
    
    return $options;

}

$prefix = '_fcw_';

class custommetabox{

	private $args;
	
	private $filters = array();

	function custommetabox($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
	
		$this->args = $args;
				
		if( function_exists('register_field')){
				
			register_field('acf_image_picker', dirname(__File__) . '/custom_fields/image-picker.php');
				
			register_field('acf_disabled_text', dirname(__File__) . '/custom_fields/disabled-text.php');
			
			register_field('acf_paragraph', dirname(__File__) . '/custom_fields/paragraph.php');
				
		}
		
		$this->add();
		
	}
	
	function add(){

		//require_once('acf/acf-lite.php');
		
		if(is_array($this->args)){
				
			foreach($this->args as $kk => $args){
			
				if(!isset($args['options'])){
					
					$args['options'] = array (
						'position' => 'normal',
						'layout' => 'default',
						'hide_on_screen' => 
						array ()
					);
				
				}
				
				if(!isset($args['location'])){
					
					$args['location'] =array (
						'rules' => 
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'post',
								'order_no' => 1,
							)
						),
						'allorany' => 'any',
					);
				
				}
				
				if(!isset($args['menu_order'])){
					
					$args['menu_order'] = 0;
				
				}
			
				foreach($args['fields'] as $k => $v){
	
					if(!isset($args['fields'][$k]['name'])){
						$args['fields'][$k]['name'] = uniqid();
					}
					
					if(!isset($args['fields'][$k]['key'])){
						$args['fields'][$k]['key'] = uniqid();
					}
					
					if(!isset($args['fields'][$k]['required'])){
						$args['fields'][$k]['required'] = 0;
					}
					
					if(!isset($args['fields'][$k]['instructions'])){
						$args['fields'][$k]['instructions'] = '';
					}
					
				}
				
				if(isset($args['versions']) && is_array($args['versions'])){
					
					foreach($args['versions'] as $key => $value){
	
						$args['id'] = $this->args[$kk]['id'].$key;
						
						$args['title'] = $this->args[$kk]['title'].' - '.$value;
						
						foreach($args['fields'] as $k => $v){
						
							if($v['type'] != 'tab'){
						
								$args['fields'][$k]['key'] = (isset($this->args[$kk]['fields'][$k]['key']) ? $this->args[$kk]['fields'][$k]['key'].$key : '');
							
							}
							
							$args['fields'][$k]['id'] = (isset($this->args[$kk]['fields'][$k]['id']) ? $this->args[$kk]['fields'][$k]['id'].$key : '');
							
							$args['fields'][$k]['class'] = (isset($this->args[$kk]['fields'][$k]['class']) ? $this->args[$kk]['fields'][$k]['class'].$key : '');
							
							$args['fields'][$k]['name'] = (isset($this->args[$kk]['fields'][$k]['name']) ? $this->args[$kk]['fields'][$k]['name'].$key : '');
							
							if(isset($this->args[$kk]['fields'][$k]['default_value']) && $this->args[$kk]['fields'][$k]['default_value'] == '[option_equiv]'){
	
								$args['fields'][$k]['default_value'] = get_option('options_'.$args['fields'][$k]['name']);
								
							}
							
							if(isset($args['fields'][$k]['sub_fields'])){
														
								array_push($this->filters, $args['fields'][$k]['name']);
															
								$number_of_rows = get_option('options_'.$args['fields'][$k]['name'], true);
									
								$args['fields'][$k]['default_value'] = $number_of_rows;
								
							}
							
							if(isset($args['fields'][$k]['conditional_logic']['rules'])){
								
								foreach($args['fields'][$k]['conditional_logic']['rules'] as $r => $rule){
									
									if(isset($rule['field'])){
										
										$args['fields'][$k]['conditional_logic']['rules'][$r]['field'] = $this->args[$kk]['fields'][$k]['conditional_logic']['rules'][$r]['field'].$key;
										
									}
									
								}
								
							}
						
						}
						
						register_field_group($args);
						
					}
					
				}else{
				
					register_field_group($args);
					
				}
			
			}
		
		}
			
	}

}