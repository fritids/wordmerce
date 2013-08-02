<?php

$physical = new physical;

class physical extends base_product{

	function physical(){
		
		$this->__construct();
		
	}
	
	function __construct(){
	
		parent::__construct();
		
		add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
		
		add_action('wp_head', array(&$this, 'inline_assets'));
		
		add_action('wordmerce/before_content', array(&$this, 'before_content'));
				
		add_action('wordmerce/allitems', array(&$this, 'allitems'), 0, 3  );
		
		add_action('wordmerce/itempage', array(&$this, 'itempage'), 10, 2 );
		
		add_action('wordmerce/confirm/verified', array(&$this, 'confirm_order' ), 10, 1);
		
		add_action('wordmerce/confirm/unverified', array(&$this, 'unconfirmed_order' ), 10, 1);
		
		$use_stock = get_field('stock_control', 'options');
		
		if($use_stock){
		
			add_action('wordmerce/basket/update', array(&$this, 'update_stock' ), 10, 1);
		
		}
		
	}
	
	function inline_assets(){
		
		echo '
			<style type="text/css">
			
				#product_image_container{
					width: '.get_option('options_main_image_width').'px;
				}
			
			</style>
		';
		
	}
	
	function wp_enqueue_scripts(){
	
		wp_enqueue_script('jquery');
		
		wp_enqueue_script( 'physical-plugins', plugins_url( 'js/plugins.js', __FILE__ ));
		
		wp_enqueue_script( 'physical-scripts', plugins_url( 'js/scripts.js', __FILE__ ), array('physical-plugins'));
		
		wp_enqueue_style( 'physical-components', plugins_url( 'css/components.css', __FILE__ ));
		
		wp_enqueue_style( 'physical-scripts', plugins_url( 'css/style.css', __FILE__ ));
		
		$js_settings = array( 'product_thumb_width' => get_option('options_thumb_image_width'), 'confirm' => get_bloginfo('url').'/'.$this->slug.'/'.$this->confirm.'/');
		
		wp_localize_script( 'physical-scripts', 'settings', $js_settings );
		
	}
	
	function before_content(){
	
		$show_breadcrumbs = get_field('show_breadcrumb', 'options');
	
		global $wp_query;
		
		//echo '<pre>'; print_r($wp_query);
		
		$return = '<div class="login_alert alert alert-error hide"><button type="button" class="close" data-dismiss="alert">&times;</button><span id="error_message"></span></div>';
		
		if($show_breadcrumbs){
		
			$return .= '<ul id="wm_breadcrumbs" class="breadcrumb">';
			
				$return .= '<li><a href="'. get_bloginfo('url') .'/'. $this->slug .'">'. $this->home_name .'</a> <span class="divider">/</span></li>';
				
				if(isset($wp_query->query_vars['type'])){
				
					switch($wp_query->query_vars['type']){
				
						case $this->cats:
					
							if($wp_query->query_vars['item'] != ''){
									
								$return .= '<li><a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->cats.'/'.$wp_query->query_vars['shop_page'].'/'.$wp_query->query_vars['item'].'">'. $wp_query->query_vars['item'] .'</a> <span class="divider">/</span></li>';
								
							}else{
			
								
							
							}
						
						break;
						
						case $this->item:
						
							if(isset($wp_query->query_vars['item'])){
						
								if($wp_query->query_vars['item'] != ''){
							
									$return .= '<li><a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->cats.'/'.$wp_query->query_vars['shop_page'].'/'.$wp_query->query_vars['item'].'">'. $wp_query->query_vars['item'] .'</a> <span class="divider">/</span></li>';
								
								}else{
							
									$return .= '<li><a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->item.'/'.$wp_query->query_vars['shop_page'].'">'. $wp_query->query_vars['shop_page'] .'</a> <span class="divider">/</span></li>';
								
								}
							
							}
						
						break;
						
						case $this->confirm:
						
						
						break;
						
						case $this->return:
						
						
						break;
						
						case $this->account:
						
						
						break;
						
						default:
						
						
						break;
					
					}
					
				}
						
			$return .= '</ul>'; //#wm_breadcrumbs
		
		}
		
		echo $return;
		
	}
	
	function allitems($cat='', $type='', $tax=''){
			
		global $wpdb, $post, $wordmerce;
		
		//print_r(get_object_taxonomies($type));
			
		$args = array( 'numberposts' => -1, 'post_type' => $type, $tax => $cat);
		
		$return = '<div id="wm_products_container" class="allow_ajax_selection">';
		
			$cards = get_posts( $args );
			
			if($cards){
			
				foreach( $cards as $post ) :	setup_postdata($post);
											
					$term_list = wp_get_object_terms($post->ID, $wordmerce->taxonomies, array("fields" => "all"));
	
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
		 
						$return .= '<a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->item.'/'.$post->post_name.'" id="'.$post->post_name.'">
							<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" class="'.$class .' design" alt="'.$post->post_title.'">';
						
							$return .= '<h4 class="item_title">'.$post->post_title.'</h4>';
						
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
	
								$return .= $start . '<a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->cats.'/'.$term->taxonomy.'/'.$term->slug.'">'.$term->name.'</a>';
								
								$ti++;
						
							}
							
							$return .= '</p>';
						
						}
					
					$return .= '</div>';
								
				endforeach; 
			
			}else{
				
				$return .= apply_filters('wm_nothing_found', '<p>Sorry, but there are no products here. How about going <a href="'.get_bloginfo('url').'/'.$this->slug.'">back to the shop?</a></p>');
				
			}
		
		$return .= '</div>';
		
		echo $return;
		
	}
	
	function itempage($name, $type){
	
		$product = get_page_by_path($name, 'OBJECT', $type);

		$images = apply_filters('wm_images', get_field('images', $product->ID), $product->ID); 
		
		$use_stock = get_field('stock_control', 'options');
		
		if($use_stock){
		
			$stock = get_post_meta($product->ID, 'stock', true);
			
			$reserved_stock = get_post_meta($product->ID, 'reserved_stock', true);
		
		}
		
		$return = '<div id="product_container">';
		
			if(is_array($images)){
		
				$return .= '<div id="product_image_container">';
								
					$return .= '<div id="wm_slider" class="flexslider">
					
						<ul class="slides">';
						
							foreach($images as $image){
							
								$image_attributes = apply_filters('wm_image_src', wp_get_attachment_image_src( $image, 'product_main' ), $product->ID); 
	
							
								$return .= '<li>
								
									<img src="'.$image_attributes[0].'" />
								
								</li>';
							
							}
						
						$return .= '</ul>
						
					</div>'; //#slider
					
					if(count($images) > 1){
					
						$return .= '<div id="wm_carousel" class="flexslider">
							
							<ul class="slides">';
							
								foreach($images as $image){
								
									$image_attributes = apply_filters('wm_image_src', wp_get_attachment_image_src( $image, 'product_thumb' ), $product->ID); 
									
									$return .= '<li>
									
										<img src="'.$image_attributes[0].'" />
									
									</li>';
								
								}
							
							$return .= '</ul>
							
						</div>'; //#carousel
					
					}
								
				$return .= '</div>'; //#product_image_container
			
			}
			
			$title = apply_filters('wm_product_title', $product->post_title, $product->ID);
			
			$return .= '<div id="product_info_container">';
			
				$return .= apply_filters('wm_before_title', '', $product->ID);
			
				$return .= '<h1 class="product_title">'.$title.'</h1>';
				
				$return .= apply_filters('wm_after_title', '', $product->ID);
				
				$return .= '<div id="product_description">'.get_field('description', $product->ID).'</div>';
			
			$return .= '</div>'; //#product_info_container
		
		$return .= '</div>'; //#product_container
		
		$return .= '<div id="product_buy_container" class="hero-unit">';
		
			$return .= '<form id="product_buy_form" method="POST">';
		
				$return .= apply_filters('wm_before_buy', '', $product->ID);
				
				$return .= '<div class="simpleCart_shelfItem">
				
					<div class="item_name hide"><a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->item.'/'.$product->post_name.'" id="'.$product->post_name.'">'.$title.'</a><div class="name_text_addon"></div></div>
					
					<div class="item_id hide">'.$product->ID.'</div>
					
					<div class="item_baseid hide">'.$product->ID.'</div>
					
					<div class="item_weight hide">'.get_field('weight',$product->ID).'</div>
								
					<p>';

						if( $stock > 0 && $use_stock){
				
							$return .= '<h2 class=""><span class="item_price">'. apply_filters('wm_product_price', '&pound;'.get_field('price', $product->ID), $product->ID) .'</span></h2><br>';
							
							$return .= apply_filters('wm_after_buy', '', $product->ID);
							
							$return .= '<br><br><div class="input-prepend input-append">
							
								<span class="add-on">Quantity</span>
								
								<input class="item_Quantity input-mini" id="appendedPrependedInput" type="text" value="1" data-max="'. $stock .'">
								
								<button class="btn item_add" href="javascript:;" type="button">Add to Order</button>
							
							</div>';
							
							$return .= '<br>' . $stock . ' currently available.<br>';
						
						}elseif($use_stock){
							
							$return .= 'OUT OF STOCK';
							
							if($reserved_stock > 0){
								
								$return .= '<br><small>There are currently '.$reserved_stock.' items being held in baskets across the site which may become avaialble soon. Please check back later.</small>';
								
							}
							
						}else{
							
							$return .= '<h2 class=""><span class="item_price">'. apply_filters('wm_product_price', '&pound;'.get_field('price', $product->ID), $product->ID) .'</span></h2><br>';
							
							$return .= apply_filters('wm_after_buy', '', $product->ID);
							
							$return .= '<br><br><div class="input-prepend input-append">
							
								<span class="add-on">Quantity</span>
								
								<input class="item_Quantity input-mini" id="appendedPrependedInput" type="text" value="1"">
								
								<button class="btn item_add" href="javascript:;" type="button">Add to Order</button>
							
							</div>';
							
						}
										
					$return .= '</p>
				
				</div>';
				
			$return .= '</form>'; //#product_buy_form
		
		$return .= '</div>'; //#product_buy_container
		
		echo $return;
		
	}
	
	function confirm_order($order_id){
	
		$o = new orders;
		
		$customer_id = $o->get_customer_id($order_id);
		
		$customer = new customers;
				
		echo '<h2>Thank you '.$customer->get_name($customer_id).'!</h2>';
		
		
	}
	
	function unconfirmed_order($order_id){
		
		echo '<p>Your payment has not yet been received.</p> ';
		
	}
	
	function update_stock($id){
		
		$previous_basket = get_option('previous_basket_'.$id);

		if(is_array($previous_basket)){
			
			foreach($previous_basket as $product => $data){
				
				$quantity = $data['quantity']; 
			
				$p_id = $data['id'];
				
				$current_reserved = get_post_meta($p_id, 'reserved_stock', true); 
				
				$current_reserved = ($current_reserved ? $current_reserved : 0);
				
				$current_stock = get_post_meta($p_id, 'stock', true);
				
				$reserved_level = ((int)$current_reserved - (int)$quantity);
				
				$stock_level = ((int)$current_stock + (int)$quantity);
				
				update_post_meta( $p_id, 'reserved_stock', $reserved_level);
				
				update_post_meta( $p_id, 'stock', $stock_level);
				
			}
			
		}
				
		$products = get_post_meta($id, 'products', true);

		if(is_array($products)){
		
			foreach($products as $product => $data){
		
				$quantity = $data['quantity']; 
			
				$p_id = $data['id'];
				
				$current_reserved = get_post_meta($p_id, 'reserved_stock', true); 
				
				$current_reserved = ($current_reserved ? $current_reserved : 0);
				
				$current_stock = get_post_meta($p_id, 'stock', true);
				
				$reserved_level = ((int)$current_reserved + (int)$quantity);
				
				$stock_level = ((int)$current_stock - (int)$quantity);
				
				update_post_meta( $p_id, 'reserved_stock', $reserved_level);
				
				update_post_meta( $p_id, 'stock', $stock_level);
		
			}
			
		}
				
		update_option( 'previous_basket_'.$id, ($products ? $products : ''));
		
	}

}