<?php
/*
Name: Link Products
Description: Link products that are associated with other products and allow them to be purchased with the original product
*/

$link_products = new link_products;

class link_products{
	
	function link_products(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		add_filter('wm_fields', array($this, 'wm_fields' ), 99999999999999, 1);
		
		add_filter('wm_base_fields', array($this, 'wm_base_fields' ), 99999999999999, 2);
				
		add_filter('wm_after_buy', array(&$this, 'wm_after_buy'), 99999, 2);
		
	}
	
	function wm_after_buy($n, $id){
		
		$extras = get_field('link_products_products', $id);
		
		if(is_array($extras)){
		
			$return = '<h2>Extras</h2>';
					
			foreach($extras as $extra){
				
				$extra = get_post($extra);
				
				$name = $extra->post_title;
				
				$desc = get_field('description', $extra->ID);
				
				$price = get_field('price', $extra->ID);
				
				$slug = $extra->post_name;
				
				$return .= '<div class="extra_container">';
				
					$return .= '<p>
						
						<span class="extra extra_name">'. $name .'</span>
						
						<span class="extra_price">&pound;'. $price .'</span>
						
						<span><a href="#modal_'. $slug .'" class="btn btn-mini btn-info" data-toggle="modal">?</a></span>
						
						<span><label class="checkbox"><input type="checkbox" data-price="'. $price .'" data-name="'. $name .'" class="add_to_product btn btn-mini" />Add</label></span>
						
					</p>
					
					<div id="modal_'. $slug .'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal_label_'. $slug .'" aria-hidden="true">';
					
						$return .= '<div class="modal-header">
							
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							
							<h3 id="modal_label_'. $slug .'">'. $name .'</h3>
							
						</div>
						
						<div class="modal-body">
						
							<p>'. $desc .'</p>
						</div>
									
					</div>'; //.modal
					
				$return .= '</div>'; //.extra_container
				
			}
			
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
				'key' => 'link_post_types',
				'label' => 'Product types to apply add on to',
				'name' => 'link_post_types',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'link_post_types',
				'class' => 'link_post_types',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'link-products',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			),
			array(
				'key' => 'link_post_types_choices',
				'label' => 'Product types to display in link section',
				'name' => 'link_post_types_choices',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'link_post_types_choices',
				'class' => 'link_post_types_choices',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'link-products',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			)
		);
		
		$pos = array_search("link-products",array_keys($metaboxes['addons']['fields']), true); 

		array_splice( $metaboxes['addons']['fields'], $pos+1, 0, $new_fields );
			
		return $metaboxes;
		
	}
	
	function wm_base_fields($meta_boxes, $post_type){
		
		if(in_array($post_type, get_option('options_link_post_types'))){
	
			$meta_boxes['link_products'] = array (
				'id' => 'link_products',
				'title' => 'Link Products',
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
						'key' => 'link_products_products',
						'label' => '',
						'name' => 'link_products_products',
						'type' => 'relationship',
						'instructions' => 'Add sub products here that will be available within this product\'s page',
						'required' => '0',
						'id' => 'link_products_products',
						'class' => 'link_products_products',
						'post_type' => get_option('options_link_post_types_choices')
					)
				)
			);
		
		};
		
		return $meta_boxes;	
		
	}
	
}