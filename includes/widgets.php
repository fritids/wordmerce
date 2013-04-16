<?php

add_action( 'widgets_init', 'wm_register_widgets' );  

function wm_register_widgets(){
	
	register_widget( 'account_info' );
	
	register_widget( 'shop_categories' );
	
	register_widget( 'shop_parent_categories' );
	
	register_widget( 'shop_best_sellers' );
	
}

class account_info extends WP_Widget {
	
	function account_info() {  
        $widget_ops = array( 'classname' => 'account_info', 'description' => 'Display the log in, log out and account links');  
        $control_ops = array( );  
        $this->WP_Widget( 'account_info', 'Account Info', $widget_ops, $control_ops );  
    }  	
    
    function widget( $args, $instance ) {
    
    	extract( $args );
    	
    	$home_page_op = get_field('shop_page', 'options');
				
		$home_page = get_permalink($home_page_op);
		    
    	echo $before_widget; 
    	
    	$customer = new customers;

    	if(!$customer->is_logged_in()){
	    
    		echo '<button class="btn btn-mini" type="button"><a href="'.$home_page.'/account">Log in</a></button>'; 
    		
    	}else{
	    	
	    	echo '<button class="btn btn-mini" type="button"><a href="'.$home_page.'/account">Your Account</a></button>'; 
	    	
	    	echo '<button class="btn btn-mini" type="button"><a href="#" id="logout_link">Log Out</a></button>'; 
	    	
    	}
	   
    	echo $after_widget; 
	    
    }
    
    public function form( $instance ) {
		echo '<p>This widget doesn\'t have any options.</p>';
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
	
}

class shop_categories extends WP_Widget {
	
	function shop_categories() {  
        $widget_ops = array( 'classname' => 'shop_categories', 'description' => 'Display a list of all categories');  
        $control_ops = array( );  
        $this->WP_Widget( 'shop_categories', 'Shop Categories', $widget_ops, $control_ops );  
    }  	
    
    function widget( $args, $instance ) {
        
    	global $wordmerce;
    	
    	$base_product = get_option('base_product');
    
    	extract( $args );
    	
    	$home_page_op = get_field('shop_page', 'options');
				
		$home_page = get_permalink($home_page_op);
			
		$title = $instance['WM_tax_title'] ? $instance['WM_tax_title'] : false;
		
		$taxonomies = $wordmerce->taxonomies;
						
		$terms = get_terms($taxonomies,array('parent' => 0, 'hide_empty' => false)); 
				    
    	echo $before_widget; 
    	    	    		
    		if($title){ ?>
    		
    			<h3><?php echo $title; ?></h3>
    		
    		<?php } 
    		
    		if(count($terms) > 0){ ?>
    		
    			<ul class="shop_categories">
    		
	    			<?php $i = 0;
	    			
	    			foreach($terms as $t){ 
	    			
	    				if($current && $current == $t->term_id){
		    				$class = 'current';
	    				}else{
		    				$class = '';
	    				} 
	    			
	    				$sub_terms = get_terms($taxonomies,array('parent' => $t->term_id, 'hide_empty' => false)); ?>
		    			
	    				<li class="<?php echo $class; ?>">
	    				
	    					<a href="<?php echo get_bloginfo('url') . '/' . $base_product['slug'] . '/' . $base_product['cats'] . '/' . $t->slug;?>"><?php echo $t->name; ?></a>
	    				
	    					<ul>
	    					
	    						<?php foreach($sub_terms as $st){ 
		    						
		    						if($current && $current == $st->term_id){
					    				$class = 'current';
				    				}else{
					    				$class = '';
				    				} ?>
	    						
	    							<li class="<?php echo $class; ?>"><a href="<?php echo get_bloginfo('url') . '/' . $base_product['slug'] . '/' . $base_product['cats'] . '/' . $st->slug;?>"><?php echo $st->name; ?></a></li>
	    						
	    						<?php } ?>
	    					
	    					</ul>
	    				
	    				</li>
	    		
	    				
		    		<?php } ?>
	    		
    			</ul>
    		
    		<?php }
	   
    	echo $after_widget; 
	    
    }
    
    public function form( $instance ) {
    
    	$instance = wp_parse_args( (array) $instance  );

    	$product_types_no = get_option('options_product_types');
    	
    	$WM_show_cat_title = isset( $instance['WM_show_cat_title'] ) ? (bool) $instance['WM_show_cat_title'] : false; ?>
    	
    	<p>
    	
    		<label for="<?php echo $this->get_field_id( 'WM_tax_title' ); ?>"><?php _e( 'Title' ); ?></label>
    	
    		<input type="text" id="<?php echo $this->get_field_id( 'WM_tax_title' ); ?>" name="<?php echo $this->get_field_name( 'WM_tax_title' ); ?>" value="<?php echo $instance['WM_tax_title']; ?>" />
    	
    	</p>
		
	<?php }

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['WM_tax_title'] = strip_tags( $new_instance['WM_tax_title'] );
								 
		return $instance;
	
	}
	
}

class shop_parent_categories extends WP_Widget {
	
	function shop_parent_categories() {  
        $widget_ops = array( 'classname' => 'shop_parent_categories', 'description' => 'Display a list of subcategories');  
        $control_ops = array( );  
        $this->WP_Widget( 'shop_parent_categories', 'Shop Parent Categories', $widget_ops, $control_ops );  
    }  	
    
    function widget( $args, $instance ) {
        
    	global $wordmerce;
    	
    	$base_product = get_option('base_product');
    
    	extract( $args );
    	
    	$home_page_op = get_field('shop_page', 'options');
				
		$home_page = get_permalink($home_page_op);
		
		$tax_term = explode('~~', $instance['WM_tax_term']);
		
		$tax = $tax_term[0];
		
		$term = $tax_term[1];
		
		$term_obj = get_term_by('id', $term, $tax);
	
		$term_name = $term_obj->name; 
		
		$show_title = $instance['WM_show_cat_title'] ? true : false;
		
		$link = $instance['WM_link_title'] ? $instance['WM_link_title'] : false;
		
		$limit = $instance['WM_tax_limit'] ? $instance['WM_tax_limit'] : '4';
				    
    	echo $before_widget; 
    	
    		$terms = get_terms($tax, array('parent' => $term, 'hide_empty' => false));
    		
    		if($show_title){ ?>
    		
    			<h2><?php echo $term_name; ?></h2>
    		
    		<?php } 
    		
    		if(count($terms) > 0){ ?>
    		
    			<ul class="shop_categories_from_parent">
    		
	    			<?php $i = 0;
	    			
	    			foreach($terms as $t){ 
		    			
		    			if($i < $limit){ ?>
	    		
	    					<li><a href="<?php echo get_bloginfo('url') . '/' . $base_product['slug'] . '/' . $base_product['cats'] . '/' . $t->slug;?>"><?php echo $t->name; ?></a></li>
	    		
	    				<?php }
	    				
		    			$i++;
	    				
	    			} ?>
	    		
    			</ul>
    		
    		<?php }
    		
	    	if($link){ ?>
		    	
		    	<a class="button" href="<?php echo get_bloginfo('url') . '/' . $base_product['slug'] . '/' . $base_product['cats'] . '/' . $term_obj->slug;?>"><?php echo $link; ?></a>
		    	
	    	<?php }
	   
    	echo $after_widget; 
	    
    }
    
    public function form( $instance ) {
    
    	$instance = wp_parse_args( (array) $instance  );

    	$product_types_no = get_option('options_product_types');
    	
    	$WM_show_cat_title = isset( $instance['WM_show_cat_title'] ) ? (bool) $instance['WM_show_cat_title'] : false;
    
    	$i=0;
    	
    	?><p>
    	
			<label for="WM_term"><?php _e( 'Show sub categories from this parent category:' ); ?></label>
			
			<select name="<?php echo $this->get_field_name( 'WM_tax_term' ); ?>" id="<?php echo $this->get_field_id( 'WM_tax_term' ); ?>"> 
		
				<?php while($i < $product_types_no){
				
					$categories_no = get_option('options_product_types_'.$i.'_categories', true);
							
					$ii = 0;
					
					while($ii < $categories_no){
					
						$cat = get_option('options_product_types_'.$i.'_categories_'.$ii.'_cat_name');
					
						$cat_name = str_replace(" ", "_", strtolower($cat));
		
						$terms = get_terms($cat_name,array('parent' => 0, 'hide_empty' => false)); ?>
							
							<?php foreach($terms as $term){ ?>
							
								<option <?php selected($instance['WM_tax_term'], $cat_name.'~~'.$term->term_id ); ?> value="<?php echo $cat_name.'~~'.$term->term_id; ?>"><?php echo $cat . ' - ' . $term->name; ?></option>
							
							<?php }
				
						$ii++;
					
					}
					
					$i++;
					
				} ?>
		
			</select>
					
    	</p>
    	
    	<p>
    	
    		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'WM_show_cat_title' ); ?>" name="<?php echo $this->get_field_name( 'WM_show_cat_title' ); ?>"<?php checked( $WM_show_cat_title ); ?> />
    		
    		<label for="<?php echo $this->get_field_id( 'WM_show_cat_title' ); ?>"><?php _e( 'Show parent category title?' ); ?></label><br />
    	
    	</p>
    	
    	<p>
    	
    		<label for="<?php echo $this->get_field_id( 'WM_tax_limit' ); ?>"><?php _e( 'Number of categories to show' ); ?></label>
    	
    		<input type="text" id="<?php echo $this->get_field_id( 'WM_tax_limit' ); ?>" name="<?php echo $this->get_field_name( 'WM_tax_limit' ); ?>" value="<?php echo $instance['WM_tax_limit']; ?>" />
    	
    	</p>
    	
    	<p>
    	
    		<label for="<?php echo $this->get_field_id( 'WM_link_title' ); ?>"><?php _e( 'If you would like to show a link to view the full range of the parent category, type the text you would like the link to contain. Otherwise just leave it blank.' ); ?></label>
    	
    		<input type="text" id="<?php echo $this->get_field_id( 'WM_link_title' ); ?>" name="<?php echo $this->get_field_name( 'WM_link_title' ); ?>" value="<?php echo $instance['WM_link_title']; ?>" />
    	
    	</p>
		
	<?php }

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['WM_tax_term'] = strip_tags( $new_instance['WM_tax_term'] );
		
		$instance['WM_show_cat_title'] = strip_tags( $new_instance['WM_show_cat_title'] );
		
		$instance['WM_link_title'] = strip_tags( $new_instance['WM_link_title'] );
		
		$instance['WM_tax_limit'] = strip_tags( $new_instance['WM_tax_limit'] );
		 
		return $instance;
	
	}
	
}

class shop_best_sellers extends WP_Widget {
	
	function shop_best_sellers() {  
        $widget_ops = array( 'classname' => 'shop_best_sellers', 'description' => 'Display a list of bestselling products');  
        $control_ops = array( );  
        $this->WP_Widget( 'shop_best_sellers', 'Shop Best Sellers', $widget_ops, $control_ops );  
    }  	
    
    function widget( $args, $instance ) {
    
    	global $wordmerce;
    	
    	$base_product = get_option('base_product');
    	
    	$cards = get_option('cards');
    	
    	$types = $wordmerce->post_types;
    	
    	$number = $instance['WM_no_products'] ? $instance['WM_no_products'] : 5;
    
    	extract( $args );

    	$args = array(
			'numberposts'     => $number, 
			'offset'          => 0,
			'orderby'         => 'meta_value', 
			'order'           => 'DESC', 
			'meta_key'        => 'sales',
			'post_type'       => $types, 
			'post_status'     => 'publish' 
		);
		
		$products = get_posts( $args );

    	echo $before_widget; ?>
    		
    		<h2><?php echo $instance['WM_best_title']; ?></h2>
    		
    		<?php if(count($products) > 0){ ?>
    		
    			<ul class="shop_categories_from_parent">
    		
	    			<?php foreach($products as $product){ ?>
	    		
	    				<li><a href="<?php echo get_bloginfo('url') . '/' . $base_product['slug'] . '/' . $cards['item'] . '/' . $product->post_name; ?>"><?php echo $product->post_title; ?></a></li>
	    		
	    			<?php } ?>
	    		
    			</ul>
    		
    		<?php }
	   
    	echo $after_widget; 
	    
    }
    
    public function form( $instance ) {
    
    	$instance = wp_parse_args( (array) $instance  ); ?>
    	
    	<p>
    	
    		<label for="<?php echo $this->get_field_id( 'WM_best_title' ); ?>"><?php _e( 'Title' ); ?></label>
    	
    		<input type="text" id="<?php echo $this->get_field_id( 'WM_best_title' ); ?>" name="<?php echo $this->get_field_name( 'WM_best_title' ); ?>" value="<?php echo $instance['WM_best_title']; ?>" />
    	
    	</p>
    	    	
    	<p>
    	
    		<label for="<?php echo $this->get_field_id( 'WM_no_products' ); ?>"><?php _e( 'Number of products to show' ); ?></label>
    	
    		<input type="text" id="<?php echo $this->get_field_id( 'WM_no_products' ); ?>" name="<?php echo $this->get_field_name( 'WM_no_products' ); ?>" value="<?php echo $instance['WM_no_products']; ?>" />
    	
    	</p>
		
	<?php }

	public function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['WM_best_title'] = strip_tags( $new_instance['WM_best_title'] );
		
		$instance['WM_no_products'] = strip_tags( $new_instance['WM_no_products'] );
		 
		return $instance;
	
	}
	
}