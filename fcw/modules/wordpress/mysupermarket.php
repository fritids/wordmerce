<?php

class mysupermarket{

	function mysupermarket(){
	
		$this->__construct();
	
	}
	
	function __construct(){
	
		add_action('wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ));
		
		add_shortcode( 'buy_now_button', array(&$this, 'buy_now_shortcode') );
		
		add_shortcode( 'ingredients', array(&$this, 'ingredients_shortcode') );
		
		$this->add_customposttype();
		
		$this->add_metaboxes();
		
	}
	
	function enqueue_scripts(){
	
		if(!wp_script_is('jquery')) {
				
		    wp_enqueue_script('jquery');
		
		}
	
		wp_enqueue_script( 'mysupermarket', 'http://www.mysupermarket.co.uk/ExternalPartners/Recipes/lib/Recipe.Base.js', array('jquery') );
		
		wp_enqueue_script( 'mysupermarket_js', plugins_url('/js/mysupermarket.js', __FILE__), array('jquery', 'mysupermarket') );
	
	}
	
	function buy_now_button($value){
	
		echo $this->get_buy_now_button($value);
	
	}
	
	function get_buy_now_button($value){
	
		$return = '<a href="http://www.mysupermarket.co.uk" ID="fcw_mysupermarket_buy_now">'.$value.'</a>';
		
		return $return;
	
	}
	
	function buy_now_shortcode($atts){
	
		extract(shortcode_atts(array(
	    	'value' => 'Buy Now'
     	), $atts));
     	
     	return $this->get_buy_now_button($value);
	
	}
	
	function ingredients(){
	
		echo $this->get_ingredients();
	
	}
	
	function get_ingredients(){
	
		global $post;
		
		$ingredients = get_post_meta($post->ID, '_fcw_ingredients', true);
		
		$ingredients = explode("\n", $ingredients);
		
		$return = '<ul id="fcw_mysupermarket-ingredients">';
		
			foreach($ingredients as $ingredient){
		
				$return .= '<li>'.$ingredient.'</li>';
		
			}
			
		$return .= '</ul>';
		
		return $return;
	
	}
	
	function ingredients_shortcode(){
	
		return $this->get_ingredients();
	
	}
	
	function add_customposttype(){
	
		$recipes = new customposttype(array(
			'name' => 'Recipes',
			'singular_name' => 'Recipe',
			'add_new' => 'Add new recipe',
			'add_new_item' => 'Add new recipe',
			'edit_item' => 'Edit recipe',
			'new_item' => 'New recipe',
			'all_items' => 'All recipes',
			'view_item' => 'View recipe',
			'search_items' => 'Search recipes',
			'not_found' => 'Nothing to see here',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon' => '',
			'menu_name' => 'Recipes',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'slug' => 'recipe',
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		));
	
	}
	
	function add_metaboxes(){
	
		$prefix = '_fcw_';
	
		$metaboxes[] = (array(
			'id'         => 'recipe_details',
			'title'      => 'Recipe Details',
			'pages'      => array( 'Recipes' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => array(
				array(
					'name' => 'Ingredients',
					'desc' => 'One ingredient per line',
					'id'   => $prefix . 'ingredients',
					'type' => 'textarea',
				),
				array(
					'name' => 'Instructions',
					'desc' => 'Preparation / cooking instructions',
					'id'   => $prefix . 'recipeInstructions',
					'type' => 'textarea_small',
				),
				array(
					'name' => 'Creator',
					'id'   => $prefix . 'creator',
					'type' => 'text_small',
				),
				array(
					'name' => 'Publisher',
					'id'   => $prefix . 'publisher',
					'type' => 'text_small',
				),
				array(
					'name' => 'Image',
					'id'   => $prefix . 'image',
					'type' => 'file',
				),
				array(
					'name' => 'Cook Time',
					'id'   => $prefix . 'cookTime',
					'type' => 'text_small',
				),
				array(
					'name' => 'Prep Time',
					'id'   => $prefix . 'prepTime',
					'type' => 'text_small',
				),
				array(
					'name' => 'Total Time',
					'id'   => $prefix . 'totalTime',
					'type' => 'text_small',
				),
				array(
					'name' => 'Recipe Yield',
					'id'   => $prefix . 'recipeYield',
					'type' => 'text_small',
				)
			)
		));
		
		new custommetabox($metaboxes);
	
	}

}