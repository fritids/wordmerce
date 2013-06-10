<?php
/*
Name: 2 Dimensional Pricing
Description: Allow pricing to be two dimensional, based on user input. Adding prices to products is done via upload of csv file and the standard price input is disabled.
*/

$twodpricing = new twodpricing;

class twodpricing{

	var $table_name; 
	
	function twodpricing(){
		
		$this->__construct();
		
	}
	
	function __construct(){
			
		global $wpdb, $post_type;
		
		$this->table_name = $wpdb->prefix . "wm_twod";
			
		$this->create_table();

		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		
		add_action('wp_head', array($this, 'enqueue_scripts_and_styles_front'), 9999999999);

		add_filter('wm_base_fields', array($this, 'wm_base_fields' ), 99999999999999, 2);
		
		add_filter('upload_mimes', array($this, 'add_csv_type'), 1, 1);
		
		add_action('wp_ajax_wm_process_csv', array($this, 'wm_process_csv'));
		
		add_action('wp_ajax_wm_undo_last_upload', array($this, 'wm_undo_last_upload'));
		
		add_filter('wm_archive_price', array($this, 'wm_archive_price'), 1, 2);
		
		add_filter('wm_fields', array($this, 'wm_fields' ), 99999999999999, 1);
		
		add_filter('wm_product_price', array($this, 'wm_product_price'), 1, 2);
		
		add_action('wp_ajax_wm_twod_price', array(&$this, 'wm_twod_price' ), 10, 1);

		add_action('wp_ajax_nopriv_wm_twod_price', array(&$this, 'wm_twod_price' ), 10, 1);
		
	}
	
	function get_current_post_type() {
		global $post, $typenow, $current_screen;
		
		if ( $post && $post->post_type )
			return $post->post_type;
		
		elseif( $typenow )
			return $typenow;
		
		elseif( $current_screen && $current_screen->post_type )
			return $current_screen->post_type;
		
		elseif( isset( $_REQUEST['post_type'] ) )
			return sanitize_key( $_REQUEST['post_type'] );
		
		return null;
	
	}
		
	function create_table(){
		
		 global $wpdb;
		 
		 $sql = "CREATE TABLE ".$this->table_name." (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  post_id mediumint(9) NOT NULL,
		  width mediumint(9) NOT NULL,
		  height mediumint(9) NOT NULL,
		  price DECIMAL(10, 2) NOT NULL,
		  update_id int(20) NOT NULL,
		  UNIQUE KEY id (id)
		);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
	}
	
	function enqueue_scripts_and_styles(){
	
		wp_enqueue_script('twodpricing_js', plugins_url('/js/scripts.js',  __FILE__ ));
		
		wp_enqueue_style('twodpricing_css', plugins_url('/css/style.css',  __FILE__ ));
	
	}
	
	function enqueue_scripts_and_styles_front(){
		
		wp_enqueue_script('twodpricing_js_front', plugins_url('/js/scripts_front.js',  __FILE__ ), 1, array('jquery'));
		
		wp_localize_script( 'twodpricing_js_front', 'options', array('ajaxurl' => admin_url( 'admin-ajax.php')));
		
	}
	
	function wm_base_fields($meta_boxes, $post_type){

		if(in_array($post_type, get_option('options_twod_post_types'))){
	
			$meta_boxes['upload_csv'] = array (
				'id' => 'upload_csv',
				'title' => '2 Dimensional Pricing Matrix',
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
							'value' => $post_type,
							'order_no' => 1,
						),
					),
					'allorany' => 'any',
				),
				'fields' => array(
					array (
						'key' => 'csv',
						'label' => 'Upload CSV',
						'name' => 'csv',
						'type' => 'file',
						'instructions' => 'Upload your csv file here.<br>The CSV must conform to a specific format. For an example of the format please <a href="'.plugins_url('/example_csv.csv',  __FILE__ ).'">download an example csv file by clicking here</a>. The first row contains the values for width and the first column contains the values for height.',
						'required' => '1',
						'id' => 'map_fields',
						'class' => 'map_fields',
					),
					array (
						'key' => 'function',
						'label' => '',
						'name' => 'function',
						'type' => 'function',
						'instructions' => '',
						'required' => '0',
						'id' => 'function',
						'class' => 'function',
						'value' => array($this, 'upload_csv_field')
					),
					array (
						'key' => 'wm_twod_update_id',
						'label' => '',
						'name' => 'wm_twod_update_id',
						'type' => 'hidden',
						'instructions' => '',
						'required' => '0',
						'id' => 'wm_twod_update_id',
						'class' => 'wm_twod_update_id'
					)
				)
			);
			
			unset($meta_boxes['product_money']);
		
		};

		//print_r($meta_boxes);
		
		return $meta_boxes;
		
	}
	
	function wm_fields($metaboxes){
	
		global $wordmerce;
		
		$types = array();
		
		foreach($wordmerce->post_types as $type){
			
			$types[$type] = $type;
			
		}
		
		$new_fields = array( 
			array(
				'key' => 'twod_post_types',
				'label' => 'Product types to apply add on to',
				'name' => 'twod_post_types',
				'type' => 'select',
				'multiple' => 1,
				'order_no' => 0,
				'instructions' => 'Select one or more.',
				'id' => 'twod_post_types',
				'class' => 'twod_post_types',
				'choices' => $types,
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => '2-dimensional-pricing',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			),
			array(
				'key' => 'twod_settings_width',
				'label' => 'Width Label',
				'name' => 'twod_settings_width',
				'type' => 'text',
				'order_no' => 0,
				'instructions' => 'The label to assign to the width axis.',
				'id' => 'twod_settings_width',
				'class' => 'twod_settings_width',
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => '2-dimensional-pricing',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			),
			array(
				'key' => 'twod_settings_height',
				'label' => 'Height Label',
				'name' => 'twod_settings_height',
				'type' => 'text',
				'order_no' => 0,
				'instructions' => 'The label to assign to the height axis.',
				'id' => 'twod_settings_height',
				'class' => 'twod_settings_height',
				'conditional_logic' => array (
					'status' => '1',
					'rules' => 
					array (
						0 => 
						array (
							'field' => '2-dimensional-pricing',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				)
			)
		);
		
		$pos = array_search("2-dimensional-pricing",array_keys($metaboxes['addons']['fields']));
		
		array_splice( $metaboxes['addons']['fields'], $pos+1, 0, $new_fields );
					
		return $metaboxes;
		
	}
	
	function upload_csv_field(){
	
		global $wpdb, $post;

		if ( get_field('wm_twod_update_id') == '') {
						

					
		}else{
		
			$results = $wpdb->get_results('SELECT width, height, price FROM '.$this->table_name.' WHERE post_id = '.$post->ID.' AND update_id = '.get_field('wm_twod_update_id'));
			
			$cols = array();
			
			$rows = array();
			
			foreach($results as $r){
			   	
			   	$cols[$r->width][$r->height] = $r->price;
			   	
		   	}
		   	
		   	foreach($results as $r){
			   	
			   	$rows[$r->height][$r->width] = $r->price;
			   	
		   	}
		   	
		   	$colspan = count($cols)+1;
		   	
		   //echo '<pre>';print_r($cols); print_r($rows);
			
			echo '<p>Latest upload was on '.date('d-m-Y @ H:i',get_field('wm_twod_update_id')).' and the following pricing structure was added:</p>';
			
			echo '<table class="wp-list-table widefat wm_twod">
				<tr>
					<td style="text-align:center;" colspan="'.$colspan.'">&larr; '.get_field('twod_settings_width', 'option').' &rarr;</td>
				<tr>
				<tr>
					<td>&darr; '.get_field('twod_settings_height', 'option').' &darr;</td>';
				
					foreach( $cols as $col => $v){
						echo '<th class="top">'.$col.'</th>';
					}
        
				echo '</tr>';
                
    
				foreach( $rows as $row => $v){ 
	
					echo '<tr>';
		
					$i = 0;
					
					echo '<th class="left">'.$row.'</th>';
		
					foreach( $cols as $col){ 
							
						if(isset($col[$row])){
			
							echo '<td>'.$col[$row].'</td>';
			
						}elseif($i == 0){
			
							echo '<td><strong>'.$row.'</strong></td>';
				
						}else{
			
							echo '<td></td>';
			
						}
			
						$i++;
		
					}
		
					echo '</tr>';
    	
				}
    
			echo '</table>
			
			<p><a id="wm_undo_last_upload" href="'.get_field('wm_twod_update_id').'">Undo last upload</a></p>';
			
			//print_r($results);
			
		}
		
	}
	
	function add_csv_type( $existing_mimes=array()){
	   
	    $existing_mimes['csv'] ='text/csv';
	   
	    return $existing_mimes;
    		
	}
	
	function wm_process_csv(){
		
		global $wpdb; // this is how you get access to the database
		
		$path = get_attached_file($_POST['csv']);
		
		$post_id = $_POST['post_id'];
				
		$row = 1;
		
		$update_id = time();
		
		//$rows_affected = $wpdb->query('DELETE FROM '.$this->table_name.' WHERE post_id = '.$post_id);
		
		if (($handle = fopen($path, "r")) !== FALSE) {
		
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			
				$num = count($data);
			
				if($row > 1){
					
					 for ($c=0; $c < $num; $c++) {
					 
					 	if($head[$c] != '' && $data[0] != ''){
			            			            
			            	$rows_affected = $wpdb->insert( $this->table_name, array( 'post_id' => $post_id, 'height' => $data[0], 'width' => $head[$c], 'price' => $data[$c], 'update_id' => $update_id ) );
			            
			            }
			        
			        }
		            
	            }else{
		            
		            $head = array();
	            
	            	 for ($c=0; $c < $num; $c++) {
			            
			          	$head[$c] = $data[$c];  
			         				        
			        }
			        		            
	            }
	            
	            $row++;
	            			            
	        }
		    
		    fclose($handle);
		    
		    echo $update_id;

		}else{
			
			echo '';
			
		}
		
        die(); // this is required to return a proper result
		
	}
	
	function wm_undo_last_upload(){
	
		global $wpdb,$acf;
	
		$post_id = $_POST['post_id'];
		
		$update_id = $_POST['update_id'];
		
		$update_ids = $wpdb->get_results('SELECT DISTINCT(update_id) FROM `'.$this->table_name.'` ORDER BY update_id DESC LIMIT 2');
				
		$rows_affected = $wpdb->query('DELETE FROM '.$this->table_name.' WHERE post_id = '.$post_id.' AND update_id = '.$update_id);
		
		echo $update_ids[1]->update_id;
		
		die();
		
	}
	
	function wm_archive_price($price, $id){
		
		return false;
		
	}
	
	function wm_product_price($n, $id){
	
		$min_max_width_height = $this->get_min_max_width_height($id);
	
		$return = '<h2 class="item_price updated_price"><span>Type a '. get_field('twod_settings_width', 'option') .' and '. get_field('twod_settings_height', 'option') .' to see prices.</span></h2>';
	
		$return .= '<label for="width">'. get_field('twod_settings_width', 'option') .'</label><input title="'. $min_max_width_height['min_width'] .' - '. $min_max_width_height['max_width'] .'" alt="'. $id .'" data-toggle="tooltip" data-container="body" data-trigger="focus" id="wm_width" name="width" type="text" class="tooltipthis small left">';
		
		$return .= '<label for="height">'. get_field('twod_settings_height', 'option') .'</label><input alt="'. $id .'" title="'. $min_max_width_height['min_height'] .' - '. $min_max_width_height['max_height'] .'" alt="'. $id .'" data-toggle="tooltip" data-container="body" data-trigger="focus" id="wm_height" name="height" type="text" class="tooltipthis small left">';
		
		$return .= '<div id="2dspin" class="spin"></div>';
		
		return $return;
		
	}
	
	function get_min_max_width_height($id){
		
		global $wpdb;
	
		$min_width = $wpdb->get_results('SELECT `width` FROM `'.$this->table_name.'` WHERE `post_id` = '. $id .' ORDER BY `width` ASC LIMIT 1');
		
		$max_width = $wpdb->get_results('SELECT `width` FROM `'.$this->table_name.'` WHERE `post_id` = '. $id .' ORDER BY `width` DESC LIMIT 1');
		
		$min_height = $wpdb->get_results('SELECT `height` FROM `'.$this->table_name.'` WHERE `post_id` = '. $id .' ORDER BY `height` ASC LIMIT 1');
		
		$max_height = $wpdb->get_results('SELECT `height` FROM `'.$this->table_name.'` WHERE `post_id` = '. $id .' ORDER BY `height` DESC LIMIT 1');
		
		return array(
			'min_width' => $min_width[0]->width,
			'max_width' => $max_width[0]->width,
			'min_height' => $min_height[0]->height,
			'max_height' => $max_height[0]->height
		);
		
	}
	
	function calculate($width, $height, $id){
	
		global $wpdb;
	
		$results = $wpdb->get_results('SELECT * FROM `'.$this->table_name.'` WHERE `width` >= '. $width . ' AND `height` >= '. $height .' AND `post_id` = '. $id .' ORDER BY `price` ASC LIMIT 1');
	    		
		if($results){
			return $results[0]->price;
		}else{
			return false;
		}
	    
    }
    
    function wm_twod_price(){
	    
	    echo $this->calculate($_POST['width'], $_POST['height'], $_POST['id']);
	    
    }
	
}