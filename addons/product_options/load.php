<?php
/*
Name: Product Options
Description: Add options to products
*/

$product_options = new product_options;

class product_options{
	
	function product_options(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		add_filter('wm_fields', array($this, 'wm_fields' ), 99999999999999, 1);
		
		add_filter('wm_base_fields', array($this, 'wm_base_fields' ), 99999999999999, 2);
				
		add_filter('wm_after_buy', array(&$this, 'wm_after_buy'), 99999, 2);
		
	}
	
	function wm_after_buy($n, $id){
	
		$return = '';
		
		$options = get_post_meta($id, 'product_options_options', true);

		if($options != '' && $options != 0){
		
/* 			$return = '<h2>Options</h2>'; */
			
			$return = '<select class="add_to_product">';
			
			$i = 0;
					
			while($i < $options){
		
				$name = get_post_meta($id, 'product_options_options_'.$i.'_option_name', true);
				
				$price = get_post_meta($id, 'product_options_options_'.$i.'_option_price', true);
									
				$return .= '<option data-price="'. $price .'" data-name="'. $name .'" class="add_to_product btn btn-mini" />'.$name.($price == '' ? '' : ' + &pound;'.$price).'</option>'; //.option_container
				
				$i++;
				
			}
			
			$return .= '</select>';
			
		}
				
		return $return;
		
	}
	
	function wm_fields($metaboxes){
			
		global $wordmerce;
		
		$types = array();
		
		foreach($wordmerce->post_types as $type){
			
			$types[$type] = $type;
			
		}
		
		$new_fields = array( 
			array(
				'key' => 'product_options_types',
				'label' => 'Product types to apply add on to',
				'name' => 'product_options_types',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'product_options_types',
				'class' => 'product_options_types',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'product-options',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			)
		);
		
		$pos = array_search("product-options",array_keys($metaboxes['addons']['fields']), true); 

		array_splice( $metaboxes['addons']['fields'], $pos+1, 0, $new_fields );
			
		return $metaboxes;
		
	}
	
	function wm_base_fields($meta_boxes, $post_type){
		
		if(in_array($post_type, get_option('options_product_options_types'))){
	
			$meta_boxes['product_options'] = array (
				'id' => 'product_options',
				'title' => 'Product Options',
				'options' => array (
					'position' => 'side',
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
							'value' => $post_type,
							'order_no' => 1,
						),
					),
					'allorany' => 'any',
				),
				'fields' => array(
					array(
						'key' => 'product_options_options',
						'label' => 'Product Options',
						'name' => 'product_options_options',
						'type' => 'repeater',
						'order_no' => 0,
						'instructions' => 'If you enter a price it will be added to the products base price.',
						'required' => 0,
						'conditional_logic' => array (
							'status' => 0,
							'rules' => 
							array (
								0 => 
								array (
									'field' => 'null',
									'operator' => '==',
								),
							),
							'allorany' => 'all',
						),
						'sub_fields' => array (
							'field_2' => array (
								'label' => 'Name',
								'name' => 'option_name',
								'type' => 'text',
								'instructions' => '',
								'column_width' => '',
								'default_value' => '',
								'formatting' => 'html',
								'order_no' => 0,
								'key' => 'option_name',
							),
							'field_3' => array (
								'label' => 'Price',
								'name' => 'option_price',
								'type' => 'text',
								'column_width' => '',
								'default_value' => '',
								'formatting' => 'html',
								'order_no' => 1,
								'key' => 'option_price',
							),
						),
						'row_min' => 0,
						'row_limit' => '',
						'layout' => 'table',
						'button_label' => 'Add Option',
					)
				)
			);
		
		};
		
		return $meta_boxes;	
		
	}
	
}