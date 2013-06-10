<?php
/*
Name: Related Products
Description: Manually associate related products
*/

$related_products = new related_products;

class related_products{
	
	function related_products(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		add_filter('wm_fields', array($this, 'wm_fields' ), 99999999999999, 1);
		
		add_filter('wm_base_fields', array($this, 'wm_base_fields' ), 99999999999999, 2);
		
	}
	
	function wm_fields($metaboxes){
			
		global $wordmerce;
		
		$types = array();
		
		foreach($wordmerce->post_types as $type){
			
			$types[$type] = $type;
			
		}
		
		$new_fields = array( 
			array(
				'key' => 'related_post_types',
				'label' => 'Product types to apply add on to',
				'name' => 'related_post_types',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'related_post_types',
				'class' => 'related_post_types',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'related-products',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			),
			array(
				'key' => 'related_post_types_choices',
				'label' => 'Product types to display in related products section',
				'name' => 'related_post_types_choices',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'related_post_types_choices',
				'class' => 'related_post_types_choices',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'related-products',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			)
		);
		
		$pos = array_search("related-products",array_keys($metaboxes['addons']['fields']), true); 

		array_splice( $metaboxes['addons']['fields'], $pos+1, 0, $new_fields );
			
		return $metaboxes;
		
	}
	
	function wm_base_fields($meta_boxes, $post_type){
		
		if(in_array($post_type, get_option('options_related_post_types'))){
	
			$meta_boxes['related_products'] = array (
				'id' => 'related_products',
				'title' => 'Related Products',
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
					array (
						'key' => 'related_products_products',
						'label' => '',
						'name' => 'related_products_products',
						'type' => 'relationship',
						'instructions' => 'Add sub products here that will be available as related products this product\'s page',
						'required' => '0',
						'id' => 'related_products_products',
						'class' => 'related_products_products',
						'post_type' => get_option('options_related_post_types_choices')
					)
				)
			);
		
		};
		
		return $meta_boxes;	
		
	}
	
}