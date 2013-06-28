<?php
/*
Name: Print Labels
Description: Print labels for an order
*/

$print_invoice = new print_invoice;

class print_invoice{
	
	function print_invoice(){
		
		$this->__construct();
		
	}
	
	function __construct(){
	
		add_filter('wm_fields', array($this, 'wm_fields' ), 99999999999999, 1);
	
	}
	
	function wm_fields($meta_boxes, $post_type){
	
		$meta_boxes['print_invoice'] = array (
			'id' => 'print_invoice',
			'title' => 'Print',
			'options' => array (
				'position' => 'normal',
				'layout' => 'default',
				'hide_on_screen' => 
				array (
					'the_content',
					'excerpt',
					'custom_fields',
					'discussion',
					'comments',
					'revisions',
					'slug',
					'author',
					'format',
					'featured_image',
					'categories',
					'tags',
					'send-trackbacks'
				)
			),
			'location' => array (
				'rules' => 
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'orders',
						'order_no' => 1,
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array (
					'key' => 'function',
					'label' => '',
					'name' => 'function',
					'type' => 'function',
					'instructions' => '',
					'required' => '0',
					'id' => 'function',
					'class' => 'function',
					'value' => array($this, 'show_print_options')
				)
			)
		);

		//print_r($meta_boxes);
		
		return $meta_boxes;
		
	}
	
	function show_print_options(){
		
		echo 'This is where the printy stuff will be :)';
		
	}
	
}