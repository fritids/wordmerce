<?php
/*
Name: Taxonomy Selector
Description: Allow users to navigate your shop with a menu driven by your product categories
*/

$taxonomyselector = new taxonomyselector;

class taxonomyselector{
	
	function taxonomyselector(){
		
		$this->__construct();
		
	}
	
	function __construct(){
	
		add_action('wp_head', array($this, 'enqueue_scripts_and_styles_front'), 9999999999);
		
		add_action('wordmerce/before_content', array(&$this, 'before_content'));	
		
		add_action('wp_ajax_search_products', array(&$this, 'search_products' ));

		add_action('wp_ajax_nopriv_search_products', array(&$this, 'search_products' ));
		
	}
	
	function enqueue_scripts_and_styles_front(){
		
		wp_enqueue_style('taxonomy_selector', plugins_url('/css/style.css',  __FILE__ ));
		
		wp_enqueue_script('taxonomy_selector', plugins_url('/js/scripts.js',  __FILE__ ));
		
		$data = array(
			'nonce' => wp_create_nonce( 'nonce' ),
			'admin' => get_bloginfo('url') . '/wp-admin/admin-ajax.php'
		);

		wp_localize_script( 'taxonomy_selector', 'tpc_ts_variables', $data );
		
		
	}
	
	function before_content(){
	
		global $wordmerce;
		
		$base_product = new base_product;
		
		if(is_array($wordmerce->taxonomies)){
		
			$num_tax = (count($wordmerce->taxonomies)+1);
			
			$width = (100-(4*$num_tax))/$num_tax;
		
			$return = '<ul id="taxonomy_selector_menu" class="navbar-inner">';
			
				$return .= ' <li style="width:'.$width.'%;"><a href="'.get_bloginfo('url').'/'.$base_product->slug.'" class="">All</a>';
			
				foreach($wordmerce->taxonomies as $tax){
				
					$taxonomy = get_taxonomy($tax);
							
					$return .= ' <li style="width:'.$width.'%;"><a alt="'.$tax.'" href="#" class="drop"><span class="number_selected"></span>'.$taxonomy->labels->name.'</a>
					
						<div class="dropdown_5columns">';
						
							$terms = get_terms($tax);
							
							if(is_array($terms)){
								
								foreach($terms as $term){

									$return .= '<span class="tax_item"><a rel="'.$tax.'" alt="'.$term->slug.'" href="'.get_bloginfo('url').'/'.$base_product->slug.'/'.$base_product->cats.'/'.$term->taxonomy.'/'.$term->slug.'">'.$term->name.'</a>';
									
										$img_id =  get_field('tax_image', $tax.'_'.$term->term_id);
									
										if($img_id != ''){
									
											$src = wp_get_attachment_image_src( $img_id );
									
											$return .= '<a rel="'.$tax.'" alt="'.$term->slug.'" href="'.get_bloginfo('url').'/'.$base_product->slug.'/'.$base_product->cats.'/'.$term->taxonomy.'/'.$term->slug.'"><img src="'.$src[0].'" /></a>';
									
										}
										
									$return .= '</span>';
									
								}
								
							}
						
					$return .= '</li>';
					
				}
					
			$return .= '</ul>';
			
		}
		
		echo $return;
		
	}
	
	function search_products(){
	
		wp_reset_query();
	
		global $wordmerce, $wp_taxonomies;
		
		$base_product = new base_product;
				
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'nonce' ) )
        	die ( 'Busted!');
        	
        if(isset($_POST['taxonomies'])){
                
	        $args = array(
				'order'    => 'ASC'
			);
	        
	        foreach($_POST['taxonomies'] as $key => $value){
	        
	        	$taxonomy = get_taxonomy($key);
	
	        	$post_type = $wp_taxonomies[$key]->object_type[0];
	
	        	$args['post_type'][] = $post_type;
	        
	        	$terms = array();
	        
	        	foreach($value as $term){
		        	
		        	$terms[] = $term;
		        	
	        	};
	        	
	        	$terms = implode(',', $terms);
		        
		        $args[$key] = $terms;
		        
	        };
	        		
			$query = new WP_Query( $args );
	
			// The Loop
			if ( $query->have_posts() ) {
	
				while ( $query->have_posts() ) {
	
					$query->the_post();
					
					global $post;
						
					$term_list = wp_get_object_terms(get_the_ID(), $wordmerce->taxonomies, array("fields" => "all"));
		
					$price = apply_filters('wm_archive_price', get_field('price', $post->ID), $post->ID);
					
					$class = "";
					
					if(!is_wp_error($term_list)){
					
						foreach($term_list as $term){
							
							$class .= $term->slug." ";
							
						}
					
					}
					
					$images = apply_filters('wm_images', get_field('images', $post->ID) , $post->ID); 
					
					$image_attributes = apply_filters('wm_image_src', wp_get_attachment_image_src( $images[0], 'product_thumb' ), $post->ID); 
					
					$return .= '<div class="item_container">';
					
					$return .= apply_filters('wm_inside_item_container', ''); 
		 
						$return .= '<a href="'.get_bloginfo('url').'/'.$base_product->slug.'/'.$base_product->item.'/'.$post->post_name.'" id="'.$post->post_name.'">
							<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" class="'.$class .' design" alt="'.$post->post_title.'">';
						
							$return .= '<h4 class="item_title">'.get_the_title().'</h4>';
						
							$return .= ($price ? '<p class="item_price">&pound;'.$price.'</p>' : '');
							
						$return .= '</a>';
							
						if(count($term_list) > 0 && !is_wp_error($term_list)){
						
							$return .= '<p class="terms">In: ';
							
							$ti = 0;
														
							foreach ($term_list as $term){
						
								if($ti == 0){
									$start = '';
								}else{
									$start = ', ';
								}
		
								$return .= $start . '<a href="'.get_bloginfo('url').'/'.$base_product->slug.'/'.$base_product->cats.'/'.$term->taxonomy.'/'.$term->slug.'">'.$term->name.'</a>';
								
								$ti++;
						
							}
							
							$return .= '</p>';
						
						}
					
					$return .= '</div>';
	
				}
	
			} else {
				$return = '<h2>Nothing found...</h2><p>Please narrow your search criteria...</p>';
			}
			/* Restore original Post Data */
			wp_reset_postdata();
			
			echo $return;
			
			// Reset Query
			wp_reset_query();

		}else{
			
			$return = '';
			
		}
		
		die();
		
	}
	
}