<?php

class customposttype{

	private $args;
	
	private $title_text, $columns, $functions;
	
	function customposttypes($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
	
		$this->args = $args;
			
		add_action( 'init', array( &$this, 'add' ) );
			
	}
	
	public function name(){
	
		extract($this->args);

		return (string) strtolower(str_replace(' ', '_', $name));	
		
	}
	
	function add(){
				
		extract($this->args);
		
		$labels = array(
			'name' => _x($name, 'post type general name', 'fcw_wordpress'),
			'singular_name' => _x($singular_name, 'post type singular name', 'fcw_wordpress'),
			'add_new' => _x($add_new, 'book', 'fcw_wordpress'),
			'add_new_item' => __($add_new_item, 'fcw_wordpress'),
			'edit_item' => __($edit_item, 'fcw_wordpress'),
			'new_item' => __($new_item, 'fcw_wordpress'),
			'all_items' => __($all_items, 'fcw_wordpress'),
			'view_item' => __($view_item, 'fcw_wordpress'),
			'search_items' => __($search_items, 'fcw_wordpress'),
			'not_found' =>  __($not_found, 'fcw_wordpress'),
			'not_found_in_trash' => __($not_found_in_trash, 'fcw_wordpress'), 
			'parent_item_colon' => $parent_item_colon,
			'menu_name' => __($menu_name, 'fcw_wordpress')
		
		);
		$args = array(
			'labels' => $labels,
			'public' => $public,
			'publicly_queryable' => $publicly_queryable,
			'show_ui' => $show_ui, 
			'show_in_menu' => $show_in_menu, 
			'query_var' => $query_var,
			'rewrite' => array( 'slug' => _x( $slug, 'URL slug', 'fcw_wordpress' ), 'with_front' => FALSE ),
			'capability_type' => $capability_type,
			'has_archive' => $has_archive, 
			'hierarchical' => $hierarchical,
			'menu_position' => $menu_position,
			'menu_icon' => (isset($menu_icon) ? $menu_icon : null),
			'supports' => $supports
		); 
		
		register_post_type(strtolower(str_replace(' ', '_', $name)), $args);
		  	
	}
	
	public function set_title_text($text){
	
		$this->title_text = $text;
		
		add_filter('gettext',array(&$this, 'edit_title_text'));
		
	}
	
	function edit_title_text( $input ) {
	
		extract($this->args);

    	global $post_type;

    	if( is_admin() && 'Enter title here' == $input && strtolower(str_replace(' ', '_', $name)) == $post_type )
    	    return $this->title_text;

    	return $input;
    
    }
    
    function edit_columns($columns, $functions){
    
    	$this->columns = $columns;
    	
    	$this->functions = $functions;
    
    	extract($this->args);
	    
	    add_filter('manage_edit-'.$slug.'_columns', array( &$this, 'columns'));
	    
	    add_action('manage_'.$slug.'_posts_custom_column', array( &$this, 'manage_columns'), 10, 2);

    }
    
    function columns($columns){
    
    	$columns = $this->columns;
	    	    
	    return $columns;
	    
    }
    
    function manage_columns($column_name, $id){
    	    
	    if(array_key_exists($column_name, $this->functions)){
		    
		    call_user_func($this->functions[$column_name], $id);
		    
	    }
	    
    }

}