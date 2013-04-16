<?php

class customtaxonomy{

	private $args, $taxonomy, $terms = array();
	
	function customtaxonomy($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
	
		$this->args = $args;
		
		extract($this->args);
		
		$this->taxonomy = strtolower( str_replace(' ', '_', $name ) );
	
		add_action( 'init', array( &$this, 'create' ) );
	
	}
	
	function create(){
				
		extract($this->args);
				
		 $labels = array(
		    'name' => _x( $name, 'taxonomy general name' ),
		    'singular_name' => _x( $singular_name, 'taxonomy singular name' ),
		    'search_items' =>  __( $search_items),
		    'all_items' => __( $all_items ),
		    'parent_item' => __( $parent_item ),
		    'parent_item_colon' => __( $parent_item_colon ),
		    'edit_item' => __( $edit_item ), 
		    'update_item' => __( $update_item ),
		    'add_new_item' => __( $add_new_item ),
		    'new_item_name' => __( $new_item_name ),
		    'menu_name' => __( $menu_name ),
		  ); 	
		
		  register_taxonomy($this->taxonomy,$post_types, array(
		    'hierarchical' => $hierarchical,
		    'labels' => $labels,
		    'show_ui' => $show_ui,
		    'query_var' => $query_var,
		    'rewrite' => (isset($rewrite) ? $rewrite : array()) //array( 'slug' => 'genre' ),
		  ));
		  	
	}
	
	function add($term){
	
		if(is_array($term)){
		
			foreach($term as $t){
				
				array_push($this->terms, $t);
				
			}
			
		}else{
	
			array_push($this->terms, $term);
		
		}
	
		add_action( 'init', array( &$this, 'add_taxonomy' ) );
		
	}
	
	function add_taxonomy(){
	
		foreach($this->terms as $term){
		
			if(!term_exists($term, $this->taxonomy)){
		
				wp_insert_term( $term, $this->taxonomy );
			
			}
		
		}
		
	}

}