<?php
/*
Plugin Name: Wordmerce
Plugin URI: http://fishcantwhistle.com
Description: 
Version: 1.0
Author: Fish Can't Whistle
*/

/*
if(session_id() == '')
     session_start();
*/

global $wpdb;

if(!empty($wpdb->prefix)) {
  $wp_table_prefix = $wpdb->prefix;
} else if(!empty($table_prefix)) {
  $wp_table_prefix = $table_prefix;
}

define('curreny_code', "GBP");

define('curreny_symbol_raw', "£");

define('currency_symbol', "&pound;");

define('weight_unit', "g");

define('MANUAL_REMOVE', false);

if (!defined("TPC_location_data_table")) { define("TPC_location_data_table", "{$wp_table_prefix}jm_location_data"); }

update_post_meta('129', 'sales', '21');

include_once(dirname( __FILE__ ) . '/fcw/load.php');

include_once(dirname( __FILE__ ) . '/addons/addons.php');

include_once(dirname( __FILE__ ) . '/includes/location.php');

include_once(dirname( __FILE__ ) . '/includes/customers.php');

include_once(dirname( __FILE__ ) . '/includes/orders.php');

class wordmerce{
	
	public $url, $dir, $inc_dir, $inc_url, $gateways, $product_types, $post_types, $taxonomies;
	
	private $settings_page, $settings, $product_types_no;
	
	private static $instance = false;

	public static function get_instance() {
	
    	if ( ! self::$instance ) {
	
	    	self::$instance = new self();
	
	    }
	
	    return self::$instance;
	
	}
	
	function wordmerce(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		$this->url = plugins_url('',  __FILE__ );
		
		$this->dir = dirname( __FILE__ );
		
		$this->inc_url = $this->url . '/inc';
		
		$this->inc_dir = $this->dir . '/inc';
		
		$this->product_types_no = get_option('options_product_types');
		
		$this->product_types = array();
		
		$this->post_types = array();
		
		$this->taxonomies = array();
		
		$this->gateways = array('dialogue' => 'Dialogue Mobile Payments', 'paypal' => 'Paypal', 'paymill' => 'PayMill');
		
		$this->shipping = array('flat' => 'Flat Rate', 'weight' => 'Weight Based', 'total' => 'Percentage of total');
			
		$this->add_image_sizes();
				
		$this->add_settings_page();
		
		$this->add_custom_post_types();
		
		$this->set_up_shipping();
		
		//add_filter('acf_settings', array(&$this, 'acf_settings' ));
		
		add_action('init', array($this, 'add_settings_fields') );
								
		add_action( 'admin_head', array($this, 'enqueue_scripts_and_styles') );
		
		add_action('admin_head', array($this, 'admin_css') );
		
		add_action( 'admin_menu', array($this, 'remove_add_new_pages'), 999 );
		
		if(!MANUAL_REMOVE){ add_filter( 'bulk_actions-' . 'edit-orders', '__return_empty_array' ); };
		
		if(!MANUAL_REMOVE){ add_filter( 'bulk_actions-' . 'edit-customers', '__return_empty_array' ); };
		
		add_action( 'restrict_manage_posts', array($this, 'custom_sorters') );
		
		add_action( 'admin_menu', array($this, 'remove_metaboxes') );
		
		add_filter( 'parse_query', array($this, 'custom_filters'));
		
		add_filter( 'post_updated_messages', array($this, 'custom_messages') );
		
		add_action( 'add_meta_boxes', array(&$this, 'add_events_metaboxes') );
		
		add_action( 'init', array(&$this, 'set_up_products') );
		
		//$this->set_up_products();
		
		$this->set_up_gateways();
		
		add_action( 'init', array($this, 'set_up_gateways') );
		
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
				
	}
	
	function get_url(){
		
		return $this->url;
		
	}
	
	function get_dir(){
		
		return $this->dir;
		
	}
	
	function add_image_sizes(){
		
		add_image_size( 'menu_icon', 16, 16, true );
		
		add_image_size( 'page_icon', 36, 34, true );
		
		add_image_size( 'product_main', get_option('options_main_image_width'), get_option('options_main_image_height'), true );
		
		add_image_size( 'product_thumb', get_option('options_thumb_image_width'), get_option('options_thumb_image_height'), true );
		
	}
	
	function enqueue_scripts_and_styles(){

		wp_enqueue_style( 'wordmerce-admin', $this->inc_url . '/css/admin.css');
		
	}
	
	function add_settings_page(){
	
		$args = array(
			'name' => 'WordMerce',
			'pages' => array('General', 'Product Types', 'Payment Gateway', 'Notifications', 'Add-ons')
		);
	    
	    $settingspage = new settingspage($args);
		
	}
	
	function add_settings_fields(){
		
		$meta_boxes[] = array (
			'id' => 'field_group_1',
			'title' => 'Page Fields',
			'fields' => array(
				array (
					'key' => 'product_types',
					'label' => 'Product Types',
					'name' => 'product_types',
					'type' => 'repeater',
					'instructions' => 'Add your product types here. For example "Cards" or "CD\'s".<br>By default all product types will have a title field.',
					'required' => '0',
					'id' => 'acf-repeater_group',
					'class' => 'product_types',
					'conditional_logic' => 
					array (
						'status' => '0',
						'rules' => 
						array (
							0 => 
							array (
								'field' => 'field_9',
								'operator' => '==',
								'value' => 'red',
							),
						),
						'allorany' => 'all',
					),
					'sub_fields' => 
					array (
						array (
							'key' => 'product_name',
							'label' => 'Product name',
							'name' => 'product_name',
							'type' => 'text',
							'instructions' => 'The name of your product.',
							'default_value' => '',
							'formatting' => 'html',
						),
						array (
							'key' => 'icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'instructions' => 'Upload an image to be associated with this product type.',
							'save_format' => 'object',
							'preview_size' => 'page_icon',
						),
						array (
							'key' => 'type',
							'label' => 'Type of product',
							'name' => 'type',
							'type' => 'select',
							'instructions' => 'Choose a type of product',
							'choices' => array(
								'card' => 'eCard',
								'physical' => 'Physical Products'
							)
						),
						array (
							'key' => 'categories',
							'label' => 'Categories',
							'name' => 'categories',
							'type' => 'repeater',
							'instructions' => 'Add categories to your product',
							'required' => '0',
							'id' => 'categories',
							'class' => 'categories',
							'sub_fields' => 
							array (
								array (
									'key' => 'cat_name',
									'label' => 'Category name',
									'name' => 'cat_name',
									'type' => 'text',
									'instructions' => 'For example: "Clothing Types" or "Format" or "Sizes"',
									'default_value' => '',
									'formatting' => 'html',
								),
								array (
									'key' => 'cat_sub',
									'label' => 'Allow sub categories?',
									'name' => 'cat_sub',
									'type' => 'true_false',
									'instructions' => '',
									'default_value' => '',
								)
							),
							'row_min' => '0',
							'layout' => 'table',
							'button_label' => 'Add a Category',
						),
/*
						array (
							'key' => 'fields',
							'label' => 'Fields',
							'name' => 'fields',
							'type' => 'repeater',
							'order_no' => 17,
							'instructions' => 'Add fields to your product',
							'required' => '0',
							'id' => 'fields',
							'class' => 'fields',
							'sub_fields' => 
							array (
								array (
									'key' => 'field_name',
									'label' => 'Field name',
									'name' => 'field_name',
									'type' => 'text',
									'instructions' => '',
									'default_value' => '',
									'formatting' => 'html',
									'order_no' => 0,
								)
							),
							'row_min' => '1',
							'row_limit' => '10',
							'layout' => 'table',
							'button_label' => 'Add Another Field',
						)
*/
					),
					'row_min' => '1',
					'layout' => 'table',
					'button_label' => 'Add Another Product Type',
				)
			),
			'location' => array (
				'rules' => array (
					array (
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-product-types',
						'order_no' => '0',
					),
				),
				'allorany' => 'all',
			),
			'options' => 
			array (
				'position' => 'normal',
				'layout' => 'no_box',
				'hide_on_screen' => 
				array (
					'slug'
				),
			),
			'menu_order' => 0,
		);
		
		$meta_boxes[] = array (
			'id' => 'shop_settings',
			'title' => 'Settings',
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
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-general',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array (
					'key' => 'shop_page',
					'label' => 'Shop Page',
					'name' => 'shop_page',
					'type' => 'post_object',
					'order_no' => 12,
					'instructions' => 'The page or post to use as your shop (you must have already created this)',
					'required' => '1',
					'id' => 'shop_page',
					'class' => 'shop_page',
					'post_type' => 
					array ('page', 'post'),
					'allow_null' => '0',
					'multiple' => '0',
				),
				array (
					'key' => 'show_widget',
					'label' => 'Product archive categories widget',
					'name' => 'show_widget',
					'type' => 'true_false',
					'instructions' => 'Show a categories widget on the product archive page?',
					'default_value' => '',
					'message' => 'Yes'
				),
				array (
					'key' => 'show_breadcrumb',
					'label' => 'Breadcrumbs',
					'name' => 'show_breadcrumb',
					'type' => 'true_false',
					'instructions' => 'Show the breadcrumbs bar at the top of every page?',
					'default_value' => '',
					'message' => 'Yes'
				),
				array (
					'key' => 'stock_control',
					'label' => 'Stock Control',
					'name' => 'stock_control',
					'type' => 'true_false',
					'instructions' => 'Use stock control?',
					'default_value' => '',
					'message' => 'Yes'
				),
				array (
					'key' => 'main_image_width',
					'label' => 'Product main image width',
					'name' => 'main_image_width',
					'type' => 'text',
					'instructions' => '',
					'default_value' => '500',
				),
				array (
					'key' => 'main_image_height',
					'label' => 'Product main image height',
					'name' => 'main_image_height',
					'type' => 'text',
					'instructions' => '',
					'default_value' => '500',
				),
				array (
					'key' => 'thumb_image_width',
					'label' => 'Product thumbnail width',
					'name' => 'thumb_image_width',
					'type' => 'text',
					'instructions' => '',
					'default_value' => '150',
				),
				array (
					'key' => 'thumb_image_height',
					'label' => 'Product thumbnail height',
					'name' => 'thumb_image_height',
					'type' => 'text',
					'instructions' => '',
					'default_value' => '150',
				),
				array (
					'key' => 'colour_scheme',
					'label' => 'Colour Scheme',
					'name' => 'colour_scheme',
					'type' => 'select',
					'instructions' => '',
					'choices' => array(
						'Default' => 'Default',
						'Amelia' => 'Amelia',
						'Cerulean' => 'Cerulean',
						'Cosmo' => 'Cosmo',
						'Cyborg' => 'Cyborg',
						'Flatly' => 'Flatly',
						'Journal' => 'Journal',
						'Readable' => 'Readable',
						'Simplex' => 'Simplex',
						'Slate' => 'Slate',
						'Spacelab' => 'Spacelab',
						'Superhero' => 'Superhero',
						'United' => 'United'
					)
				),
				array (
					'key' => 'basket_placement',
					'label' => 'Basket Placement',
					'name' => 'basket_placement',
					'type' => 'radio',
					'instructions' => 'Choose how to display the basket',
					'choices' => array(
						'float-top-left' => 'Float Top Left',
						'float-top-right' => 'Float Top Right',
						'float-bottom-left' => 'Float Bottom Left',
						'float-bottom-right' => 'Float Bottom Right',
						'manual' => 'Manually insert code to template file<br>Insert the following code in your theme file where you would like the basket to render<br><code>'.htmlentities('<?php the_basket(); ?>').'</code>',
					)
				)
			)
		);
	
		$addons = new addons;
		
		$addon_fields = array();
		
		foreach($addons->addons as $addon){

			$key = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $addon['Name']), '-'));
			
			$addon_fields[$key] = array (
				'key' => $key,
				'label' => $addon['Name'],
				'name' => $key,
				'type' => 'true_false',
				'instructions' => $addon['Description'],
				'default_value' => '',
				'message' => 'Activate'
			);
	
		}
		
		$meta_boxes['addons'] = array (
			'id' => 'add_ons',
			'title' => 'Add-ons',
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
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-add-ons',
					),
				),
				'allorany' => 'any',
			),
			'fields' => $addon_fields
		);
		
		$meta_boxes[] = array (
			'id' => 'order_update',
			'title' => 'Update',
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
						'value' => 'orders',
					),
					/*
array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'customers',
					),
*/
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array (
					'key' => 'update',
					'label' => '',
					'name' => 'update',
					'type' => 'paragraph',
					'order_no' => 12,
					'value' => '<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="Update">',
					'required' => '0',
					'id' => 'update',
					'class' => 'update'
				)
			)
		);
		
		$gateways = array(
			array (
				'key' => 'payment_gateways',
				'label' => 'Choose Your Payment Gateway',
				'name' => 'payment_gateways',
				'type' => 'radio',
				'instructions' => '',
				'id' => 'payment_gateways',
				'class' => 'payment_gateways',
				'choices' => $this->gateways
			)
		);
		
		$options = maybe_unserialize(get_option('options_payment_gateways'));
		
		foreach($this->gateways as $k => $v){
			
				include_once(dirname( __FILE__ ) . '/includes/gateways/'.$k.'/fields.php');
						
		}
		
		$meta_boxes[] = array (
			'id' => 'payment_gateways',
			'title' => 'Payment Gateway',
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
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-payment-gateway',
					),
				),
				'allorany' => 'any',
			),
			'fields' => $gateways
		);
		
		$meta_boxes[] = array (
			'id' => 'notifications',
			'title' => 'Notifications',
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
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-notifications',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => 'purchase_receipts',
					'label' => 'Purchase Receipts',
					'name' => 'purchase_receipts',
					'type' => 'tab',
					'order_no' => 0,
					'instructions' => '',
					'id' => 'purchase_receipts',
					'class' => 'tab',
					'conditional_logic' => array (
						'status' => '0'
					)
				),
				array(
					'key' => 'from_name',
					'label' => 'From Name',
					'name' => 'from_name',
					'type' => 'text',
					'order_no' => 0,
					'instructions' => 'The name that purchase receipt emails will come from.',
					'id' => 'from_name',
					'class' => 'from_name',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => get_bloginfo('name')
				),
				array(
					'key' => 'from_email',
					'label' => 'From Email Address',
					'name' => 'from_email',
					'type' => 'text',
					'order_no' => 0,
					'instructions' => 'The email address that purchase receipt emails will come from.',
					'id' => 'from_email',
					'class' => 'from_email',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => get_bloginfo('admin_email')
				),
				array(
					'key' => 'receipt_title',
					'label' => 'Receipt Subject',
					'name' => 'receipt_title',
					'type' => 'text',
					'order_no' => 0,
					'instructions' => 'The subject line of the email.',
					'id' => 'receipt_title',
					'class' => 'receipt_title',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => 'Purchase Receipt - ' . get_bloginfo('name')
				),
				array(
					'key' => 'receipt_header_image',
					'label' => 'Receipt Header image',
					'name' => 'receipt_header_image',
					'type' => 'image',
					'order_no' => 0,
					'instructions' => 'An image to appear at the top of the receipt email.',
					'id' => 'receipt_header_image',
					'class' => 'receipt_header_image',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default' => ''
				),
				array(
					'key' => 'receipt_heading',
					'label' => 'Receipt Heading',
					'name' => 'receipt_heading',
					'type' => 'text',
					'order_no' => 0,
					'instructions' => 'The heading of the email.',
					'id' => 'receipt_heading',
					'class' => 'receipt_heading',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => 'Thanks for your purchase!'
				),
				array(
					'key' => 'receipt_content',
					'label' => 'Receipt Content',
					'name' => 'receipt_content',
					'type' => 'textarea',
					'order_no' => 0,
					'instructions' => 'The main body of the receipt email.',
					'id' => 'receipt_content',
					'class' => 'receipt_content',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => 'Dear %%NAME%%, 
Thanks for your recent purchase at ' . get_bloginfo('name') . '
You purchased %%ITEM_NAME%% on %%PURCHASE_DATE%% and your order number is %%ORDER_NUMBER%%.'
				),
				array(
					'key' => 'receipt_copyright',
					'label' => 'Receipt Copyright',
					'name' => 'receipt_copyright',
					'type' => 'text',
					'order_no' => 0,
					'instructions' => 'The copyright name at the bottom of the email.',
					'id' => 'receipt_copyright',
					'class' => 'receipt_copyright',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => get_bloginfo('name')
				),
				array(
					'key' => 'receipt_footer',
					'label' => 'Receipt Footer',
					'name' => 'receipt_footer',
					'type' => 'textarea',
					'order_no' => 0,
					'instructions' => 'The footer content of the receipt email.',
					'id' => 'receipt_footer',
					'class' => 'receipt_footer',
					'conditional_logic' => array (
						'status' => '0'
					),
					'default_value' => '<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a>'
				),
			)
		);
		
		$shipping_options = array(
			array (
				'key' => 'shipping',
				'label' => 'Choose Your Shipping Method',
				'name' => 'shipping',
				'type' => 'radio',
				'instructions' => '',
				'id' => 'shipping',
				'class' => 'shipping',
				'choices' => $this->shipping
			),
			array (
				'key' => 'store_pickup',
				'label' => 'Pickup in store?',
				'name' => 'store_pickup',
				'type' => 'true_false',
				'instructions' => 'Give users the option to pick up in store?',
				'default_value' => '',
				'message' => 'Yes'
			),
			array (
				'key' => 'store_locations',
				'label' => 'Store Locations',
				'name' => 'store_locations',
				'type' => 'repeater',
				'instructions' => 'Enter the name and location of your stores',
				'required' => '0',
				'id' => 'store_locations',
				'class' => 'store_locations',
				'conditional_logic' => 
				array (
					'status' => '0',
					'rules' => 
					array (
						0 => 
						array (
							'field' => 'store_pickup',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array(
						'key' => 'store_name',
						'label' => 'Store Name',
						'name' => 'store_name',
						'type' => 'text',
						'order_no' => 0,
						'id' => 'store_name',
						'class' => 'store_name',
						'conditional_logic' => array (
							'status' => '0'
						),
					),
					array(
						'key' => 'store_location',
						'label' => 'Store Location',
						'name' => 'store_location',
						'type' => 'text',
						'order_no' => 0,
						'instructions' => 'Enter a brief description of the location of the store.',
						'id' => 'store_location',
						'class' => 'store_location',
						'conditional_logic' => array (
							'status' => '0'
						),
					)
				)
			)
		);
		
		$options = maybe_unserialize(get_option('options_shipping'));
		
		foreach($this->shipping as $k => $v){
			
				include_once(dirname( __FILE__ ) . '/includes/shipping/'.$k.'/fields.php');
						
		}
		
		$meta_boxes[] = array (
			'id' => 'shipping',
			'title' => 'Shipping',
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
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-general',
					),
				),
				'allorany' => 'any',
			),
			'fields' => $shipping_options
		);
		
		$meta_boxes[] = array (
			'id' => 'user_details',
			'title' => 'Details',
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
						'value' => 'customers',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => 'customer_details',
					'label' => '',
					'name' => 'customer_details',
					'type' => 'paragraph',
					'instructions' => '',
					'id' => 'customer_details',
					'class' => 'customer_details',
					'value' => $this->customer_details_meta_box()
				),
			)
		);
		
		$meta_boxes[] = array (
			'id' => 'previous_purchases',
			'title' => 'Previous Purchases',
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
						'value' => 'customers',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => 'previous_purchases',
					'label' => '',
					'name' => 'previous_purchases',
					'type' => 'paragraph',
					'instructions' => '',
					'id' => 'previous_purchases',
					'class' => 'previous_purchases',
					'value' => $this->customer_previous_purchases_meta_box()
				),
			)
		);
		
		$meta_boxes[] = array (
			'id' => 'order_details',
			'title' => 'Details',
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
						'value' => 'orders',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => '',
					'label' => '',
					'name' => 'order_details',
					'type' => 'paragraph',
					'instructions' => '',
					'id' => 'customer_details',
					'class' => 'customer_details',
					'value' => $this->order_details_meta_box()
				),
			)
		);
		
		$meta_boxes[] = array (
			'id' => 'order_customer_details',
			'title' => 'Customer',
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
						'value' => 'orders',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => '',
					'label' => '',
					'name' => 'order_customer_details',
					'type' => 'paragraph',
					'instructions' => '',
					'id' => 'order_customer_details',
					'class' => 'order_customer_details',
					'value' => $this->order_customer_details_meta_box()
				),
			)
		);
		
		$meta_boxes[] = array (
			'id' => 'order_customer_payment_details',
			'title' => 'Payment Details',
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
						'value' => 'orders',
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array(
					'key' => '',
					'label' => '',
					'name' => 'order_customer_payment_details',
					'type' => 'paragraph',
					'instructions' => '',
					'id' => 'order_customer_payment_details',
					'class' => 'order_customer_payment_details',
					'value' => $this->order_customer_payment_details()
				),
			)
		);
	
		if(SHIPPING){
		
			$meta_boxes[] = array (
				'id' => 'order_customer_deliver_to',
				'title' => 'Deliver To',
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
							'value' => 'orders',
						),
					),
					'allorany' => 'any',
				),
				'fields' => array(
					array(
						'key' => '',
						'label' => '',
						'name' => 'order_customer_deliver_to',
						'type' => 'paragraph',
						'instructions' => '',
						'id' => 'order_customer_deliver_to',
						'class' => 'order_customer_deliver_to',
						'value' => $this->order_customer_deliver_to()
					),
				)
			);
		
		}
		
		$meta_boxes = apply_filters('wm_fields', $meta_boxes);

		$metaboxes = new custommetabox($meta_boxes);
		
	}
	
	function order_details_meta_box(){
		
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$id = $post->ID;
			
			$order = new orders;	
			
			$c_id = $order->get_customer_id($id);
			
			$customer = new customers;	
			
			$status = $order->get_order_status($id);
			
			$status_no = $order->get_order_status($id, true);
						
			$status_class = $order->get_order_status_class($id);
			
			$gateway = $order->get_data($id, 'gateway');
						
			$gateway_transaction_id = $order->get_data($id, 'gateway_transaction_id');
			
			$email_address = $customer->get_data($c_id, 'email');
			
			$e_s = $order->get_data($id, 'emails_sent');
		
			$emails_sent = is_array($e_s) ? $e_s : array();
		
			$emails_sent = array_reverse($emails_sent);
			
			$products = get_post_meta($id, 'products', true);
			
			$shipping = get_post_meta($id, 'shipping', true);
			
			$return = '<p><strong>Status:</strong> <span class="label label-'.$status_class.'">'.$status.'</span></p>';
			
			if(is_array($products)){
							
				$return .= '<div class="row-fluid"><table id="meta_prev_orders" class="table table-striped table-hover">
				
					<thead>
					
						<tr>
					
							<td>Product Name</td>
							<td>Quantity</td>
							<td>Price</td>
							<td>Sub Total</td>';
							
						$return .= '</tr>
					
					</thead>
					
					<tbody>';
				
						foreach($products as $product => $data){ 
						
							$return .= '<tr>
							
								<td>
								
									'.$product.'
								
								</td>
								
								<td>
								
									'.$data['quantity'].'
								
								</td>
								
								<td>
								
									'.$data['price'].'
								
								</td>
								
								<td>
								
									'.$data['total'].'
								
								</td>';
															
							$return .= '</tr>';
						
						}
				
					$return .= '</tbody>
				
				</table></div>';
				
				if(SHIPPING){
					
					$return .= '<p><strong>Shipping:</strong> &pound;'.$shipping.'</p>';
					
				}
				
			}
						
			if($status_no == 3){
			
				$return .= 'Email receipt was last sent to ' . $email_address . ' on ' . $emails_sent[0] . '<a data-id="'.$id.'" href="#" id="resend_email_button" class="button primary">Resend</a>';
			
			}
			
			return $return;
					
		}
		
	}
	
	function order_customer_payment_details(){
	
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$id = $post->ID;
			
			$order = new orders;	
			
			$c_id = $order->get_customer_id($id);
			
			$customer = new customers;	
			
			$gateway = $order->get_data($id, 'gateway');
						
			$gateway_transaction_id = $order->get_data($id, 'gateway_transaction_id');
			
			$payment_info = $order->get_data($id, 'payment_info');
			
			$return = '';
		
			if($gateway && $gateway_transaction_id){
			
				$return .= '<p><strong>Gateway:</strong> '.$gateway.'</p>';
				
				$return .= '<p><strong>Gateway Transaction ID:</strong> '.$gateway_transaction_id.'</p>';
			
				$return .= '<p><strong>Payment Details:</strong></p><ul>';
				
				foreach($payment_info as $key => $info){
				
					if(is_array($info)){
						
						$return .= '<li><strong>' . $key . ':</strong> <ul style="text-indent: 20px;">';
						
							foreach($info as $sub_key => $sub_info){
							
								if(is_array($sub_info)){
						
									$return .= '<li><strong>' . $sub_key . ':</strong> <ul style="text-indent: 40px;">';
									
										foreach($sub_info as $sub_sub_key => $sub_sub_info){
											
											$return .= '<li><strong>' . $sub_sub_key . ':</strong> ' . $sub_sub_info . '</li>';
											
										}
									
									$return .= '</ul></li>';
									
								}else{
								
									$return .= '<li><strong>' . $sub_key . ':</strong> ' . $sub_info . '</li>';
								
								}
								
							}
						
						$return .= '</ul></li>';
						
					}else{
					
						$return .= '<li><strong>' . $key . ':</strong> ' . $info . '</li>';
					
					}
					
				}
				
				$return .= '</ul>';
			
			}else{
				
				$return .= '<p>No payment made yet...</p>';
				
			}
						
			return $return;
			
		}
		
	}
	
	function order_customer_deliver_to(){
		
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$id = $post->ID;
			
			$order = new orders;	
			
			$c_id = $order->get_customer_id($id);
			
			$customers = new customers;	
			
			$return = '<p>'.$customers->get_name($c_id).'</p>
			<p>'.$customers->get_data($c_id, 'inputAddress1').'</p>
			<p>'.$customers->get_data($c_id, 'inputAddress2').'</p>
			<p>'.$customers->get_data($c_id, 'inputTownCity').'</p>
			<p>'.$customers->get_data($c_id, 'inputRegion').'</p>
			<p>'.$customers->get_data($c_id, 'inputPostcode').'</p>
			<p>'.$customers->get_data($c_id, 'inputCountry').'</p>';
				
			return $return;
					
		}

		
	}
	
	function order_customer_details_meta_box(){
		
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$id = $post->ID;
			
			$order = new orders;
			
			$id = $order->get_customer_id($id);
			
			if($id == '')
				return;

			$customer = new customers;
			
			$all_data = $customer->get_data($id, 'user');
			
			$email = $customer->get_data($id, 'email');
			
			$username = $customer->get_data($id, 'username');
			
			$registered_with = $customer->get_data($id, 'service');
			
			$date = get_the_time('l, F j, Y @ G:i', $id);
			
			$img = $customer->get_data($id, 'thumbnail');
			
			$data_src = ($img != '' ? $img : '/holder.js/100x100/social/text:'.$username);
			
			$return = '<img style="float: left; margin: 0px 5px 5px 0px;" data-src="'.$data_src.'" src="'.$img.'" />';
		
			$return .= '<img class="inline_image" src="'.plugins_url( 'inc/img/customer.png', __FILE__ ).'" /> '.$username;
			
			$return .= '<br><img class="inline_image" src="'.plugins_url( 'inc/img/email.png', __FILE__ ).'" /> <a href="mailto:'.$email.'">'.$email.'</a>';
			
			if(isset($all_data['link']) && $all_data['link']){ $return .= '<br><img class="inline_image" src="'.plugins_url( 'inc/img/link.png', __FILE__ ).'" /> <a href="'.$all_data['link'].'">'.$all_data['link'].'</a>'; }
			
			$return .= '<br><br style="clear:left;"><strong>Registered with:</strong> '. ucfirst($registered_with).' on '.$date;
			
			$return .= '<br><br><a href="'.get_edit_post_link( $id ).'">View Customer</a>';
		
			return $return;
					
		}
				
	}
	
	function customer_details_meta_box(){
		
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$id = $post->ID;
			
			$customer = new customers;
			
			$all_data = $customer->get_data($id, 'user');
			
			$email = $customer->get_data($id, 'email');
			
			$username = $customer->get_data($id, 'username');
			
			$registered_with = $customer->get_data($id, 'service');
			
			$date = get_the_time('l, F j, Y @ G:i', $id);
			
			$img = $customer->get_data($id, 'thumbnail');
			
			$data_src = ($img != '' ? $img : '/holder.js/100x100/social/text:'.$username);
			
			$return = '<img style="float: left; margin: 0px 5px 5px 0px;" data-src="'.$data_src.'" src="'.$img.'" />';
		
			$return .= '<img class="inline_image" src="'.plugins_url( 'inc/img/customer.png', __FILE__ ).'" /> '.$username;
			
			$return .= '<br><img class="inline_image" src="'.plugins_url( 'inc/img/email.png', __FILE__ ).'" /> <a href="mailto:'.$email.'">'.$email.'</a>';
			
			if(isset($all_data['link']) && $all_data['link']){ $return .= '<br><img class="inline_image" src="'.plugins_url( 'inc/img/link.png', __FILE__ ).'" /> <a href="'.$all_data['link'].'">'.$all_data['link'].'</a>'; }
			
			$return .= '<br><br style="clear:left;"><strong>Registered with:</strong> '. ucfirst($registered_with).' on '.$date;
		
			return $return;
					
		}
				
	}
	
	function customer_previous_purchases_meta_box(){
	
		if(isset($_GET['post'])){
		
			$post = get_post($_GET['post']);
			
			$customer = new customers;
			
			$o = new orders;
			
			$id = $post->ID;
		
			$orders = $customer->get_orders($id);
			
			$return = '';
		
			if(is_array($orders)){
				
				$return .= '<div class="row-fluid"><table id="meta_prev_orders" class="table table-striped table-hover">
				
					<thead>
					
						<tr>
					
							<td>Product</td>
							<td>Date</td>
							<td>Status</td>
							<td>View Order</td>
							
						</tr>
					
					</thead>
					
					<tbody>';
				
						foreach($orders as $order){ 
						
							$return .= '<tr class="'.$o->get_order_status_class($order->ID).'">
							
								<td>
								
									'.$o->get_order_product($order->ID).'
								
								</td>
								
								<td>
								
									'.$date = get_the_time('l, F j, Y @ G:i', $order->ID).'
								
								</td>
								
								<td>
								
									'.$o->get_order_status($order->ID).'
								
								</td>
								
								<td>
								
									<a href="'.get_edit_post_link( $order->ID ).'">View</a>
								
								</td>
							
							</tr>';
						
						}
				
					$return .= '</tbody>
				
				</table></div>';
				
			}else{
			
				$return .= '<div class="span12 alert">
				
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				
					No orders found. You should buy something!
				
				</div>';
			
			}
			
			return $return;
			
		}
		
	}
	
	function add_custom_post_types(){
		
		$i=0;
		
		while($i < $this->product_types_no){
		
			$name = get_option('options_product_types_'.$i.'_product_name');
			
			if($name != ''){
			
				$icon = get_option('options_product_types_'.$i.'_icon');
				
				$type = get_option('options_product_types_'.$i.'_type');
			
				$this->product_types[$name] = $type;
								
				if($icon != ''){
					
					$image = wp_get_attachment_image_src($icon, 'menu_icon');
					
					$icon = $image[0];
					
				}

				$args = array(
					'name' => $name,
					'singular_name' => $name,
					'add_new' => 'Add new',
					'add_new_item' => 'Add new',
					'edit_item' => 'Edit',
					'new_item' => 'New',
					'all_items' => 'All',
					'view_item' => 'View',
					'search_items' => 'Search',
					'not_found' => 'Not found',
					'not_found_in_trash' => 'Not found in trash',
					'parent_item_colon' => ':',
					'menu_name' => $name,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true, 
					'show_in_menu' => true, 
					'query_var' => true,
					'slug' => preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($name)),
					'capability_type' => 'post',
					'has_archive' => true, 
					'hierarchical' => false,
					'menu_position' => 500+$i,
					'menu_icon' => ($icon != '' ? $icon : null),
					'supports' => array( 'title' )
				);
				
				$this->post_types[] = preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($name));
				
				$new_product = new customposttype($args);
			
				$categories_no = get_option('options_product_types_'.$i.'_categories', true);
				
				$ii = 0;
				
				while($ii < $categories_no){
				
					$cat_name = get_option('options_product_types_'.$i.'_categories_'.$ii.'_cat_name');

					$args = array(
						'name' => $cat_name,
						'singular_name' => $cat_name,
						'search_items' =>  'Search',
						'all_items' => 'All ',
						'parent_item' => 'Parent ',
						'parent_item_colon' => 'Parent: ',
						'edit_item' => 'Edit', 
						'update_item' => 'Update',
						'add_new_item' => 'Add New',
						'new_item_name' => 'New',
						'menu_name' => $cat_name,
						'post_types' => array(preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($name))),
						'hierarchical' => get_option('options_product_types_'.$i.'_categories_'.$ii.'_cat_sub'),
						'labels' => true,
						'show_ui' => true,
						'query_var' => true,
/* 						'rewrite' => array( 'slug' => 'suppliers' ), */
					    
					);
					
					$this->taxonomies[] = str_replace(" ", "_", strtolower($cat_name));
					
					$categories = new customtaxonomy($args);
					
					$ii++;
					
					$tax_slug = str_replace(" ", "_", strtolower($cat_name));
					
					$tax_meta_boxes[$tax_slug] = array (
						'id' => $tax_slug.'_tax_extras',
						'title' => 'Extra Details',
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
									'param' => 'ef_taxonomy',
									'operator' => '==',
									'value' => $tax_slug
								)
							),
							'allorany' => 'all',
						),
						'fields' => array(
							array(
								'key' => 'tax_image',
								'label' => '',
								'name' => 'tax_image',
								'type' => 'image',
								'instructions' => '',
								'id' => 'tax_image',
								'class' => 'tax_image'
							),
						)
					);
				
				}
			
			}
			
			$i++;
		
		}
		
		if(isset($tax_meta_boxes)){ 
		
			$tax_meta_boxes = apply_filters('wm_fields', $tax_meta_boxes);

			$tax_metaboxes = new custommetabox($tax_meta_boxes);
			
		};
					
		if(isset($_GET['post'])){
		
			$post_name = get_post($_GET['post']);
		
			$post_name = $post_name->post_title;
			
			$date = get_the_time('l, F j, Y @ G:i', $_GET['post']);

		}else{
			
			$post_name = 'View Order';
			
			$date = '';
		}
		
		$args = array(
			'name' => 'Orders',
			'singular_name' => 'Order',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new',
			'edit_item' => $post_name . ' - ' . $date,
			'new_item' => 'New',
			'all_items' => 'All',
			'view_item' => 'View Order',
			'search_items' => 'Search Orders',
			'not_found' => 'No orders found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon' => ':',
			'menu_name' => 'Orders',
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'slug' => 'orders',
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => 600,
			'menu_icon' => plugins_url( 'inc/img/order.png', __FILE__ ),
			'supports' => array('')
		);
		
		$orders = new customposttype($args);
		
		if(!MANUAL_REMOVE){
			$orders->edit_columns(array('product' => 'Product', 'customer' => 'Customer', 'status' => 'Status', 'date' => 'Date'), array('product' => 'order_product_name', 'customer' => 'customer_column', 'status' => 'order_status'));
		}
		
		$args = array(
			'name' => 'Customers',
			'singular_name' => 'Customer',
			'add_new' => 'Add new',
			'add_new_item' => 'Add new',
			'edit_item' => $post_name,
			'new_item' => 'New',
			'all_items' => 'All',
			'view_item' => 'View Order',
			'search_items' => 'Search Customers',
			'not_found' => 'Not found',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon' => ':',
			'menu_name' => 'Customers',
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'slug' => 'customers',
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => 550,
			'menu_icon' => plugins_url( 'inc/img/customer.png', __FILE__ ),
			'supports' => array('')
		);
		
		$customers = new customposttype($args);
		
	}
	
	function admin_css(){
		
		$i=0;
		
		while($i < $this->product_types_no){
		
			$name = get_option('options_product_types_'.$i.'_product_name');
			
			$icon = get_option('options_product_types_'.$i.'_icon');
			
			if($name != '' && $icon != ''){
								
				$image = wp_get_attachment_image_src($icon, 'page_icon');
					
				$icon = $image[0];
		
				?>
				
				<style type="text/css" media="screen">
    
					#icon-edit.icon32-posts-<?php echo strtolower( $name ); ?>{

						background: url(<?php echo $icon; ?>) no-repeat!important;

					}
					
				</style>
				
				<?php
			
			}
			
			$i++;

		
		}	
		
		?>
		
		<style type="text/css" media="screen">
    
			#icon-edit.icon32-posts-orders{
				background: url(<?php echo plugins_url( 'inc/img/order_large.png', __FILE__ ); ?>) no-repeat!important;
			}
			
			#icon-edit.icon32-posts-customers{
				background: url(<?php echo plugins_url( 'inc/img/customer_large.png', __FILE__ ); ?>) no-repeat!important;
			}
			
			.add-new-h2[href="post-new.php?post_type=orders"]{
				display: none;
			}
			
			.add-new-h2[href="post-new.php?post_type=customers"]{
				display: none;
			}
			
		</style>
				
		<?php
		
	}
	
	function set_up_products(){

		foreach($this->product_types as $post => $type){
		
			$post_type = preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($post));

			include($this->dir.'/product-types/base/base.php');
			
			include($this->dir.'/product-types/'.$type.'/'.$type.'.php');
			
			$meta_boxes = apply_filters('wm_base_fields', $meta_boxes, $post_type);
			
			$metaboxes = new custommetabox($meta_boxes);
			
		}
		
		include_once(dirname(__FILE__).'/product-types/base//display/display.php');
		
		foreach($this->product_types as $post => $type){
							
			include_once($this->dir.'/product-types/'.$type.'/display/display.php');
			
		}
		
	}
	
	function remove_add_new_pages() {
		$orders = remove_submenu_page( 'edit.php?post_type=orders', 'post-new.php?post_type=orders' );
		$customers = remove_submenu_page( 'edit.php?post_type=customers', 'post-new.php?post_type=customers' );
	}
	
	function custom_sorters(){
		
		$type = 'post';
	    if (isset($_GET['post_type'])) {
	        $type = $_GET['post_type'];
	    }
	
	    if ('orders' == $type){
	        $values = array(
	        	'Show All Orders' => '',
	            'Cancelled' => '0', 
	            'In Basket' => '1',
	            'In Checkout' => '1.5',
	            'Ordered' => '2',
	            'Payment Pending' => '2.3',
	            'Payment Failed' => '10',
	            'Complete' => '3',
	            'Error' => '999'
	        );
	        ?>
	        <select name="order_status">
	        <?php
	            $current_v = isset($_GET['order_status'])? $_GET['order_status']:'';
	            foreach ($values as $label => $value) {
	                printf
	                    (
	                        '<option value="%s"%s>%s</option>',
	                        $value,
	                        $value == $current_v? ' selected="selected"':'',
	                        $label
	                    );
	                }
	        ?>
	        </select>
	        <?php
	    }
		
	}
	
	function custom_filters($query){
		
		global $pagenow;
	    $type = 'post';
	    if (isset($_GET['post_type'])) {
	        $type = $_GET['post_type'];
	    }
	    if ( 'orders' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['order_status']) && $_GET['order_status'] != '') {
	        $query->query_vars['meta_key'] = 'status';
	        $query->query_vars['meta_value'] = $_GET['order_status'];
	    }
		
	}
	
	function remove_metaboxes(){
		
		remove_meta_box('submitdiv', 'orders', 'side');
		
		remove_meta_box('slugdiv', 'orders', 'side');
		
		remove_meta_box('submitdiv', 'customers', 'side');
		
		remove_meta_box('slugdiv', 'customers', 'side');
		
	}
	
	function custom_messages($messages){
		
		$messages['orders'] = array(
		    0 => '', // Unused. Messages start at index 1.
		    1 => sprintf( __('Order updated' ) ),
		    2 => __('Custom field updated.', 'your_text_domain'),
		    3 => __('Custom field deleted.', 'your_text_domain'),
		    4 => __('Order updated.', 'your_text_domain'),
		    /* translators: %s: date and time of the revision */
		    7 => __('Order saved.', 'your_text_domain'),
		  );
		
		  return $messages;
		
	}
	
	function add_events_metaboxes() {
	
    	//add_meta_box('meta_dump', 'Meta Dump', array($this,'all_meta'), 'customers', 'normal', 'default');
    	
    	//add_meta_box('meta_dump', 'Meta Dump', array($this,'all_meta'), 'orders', 'normal', 'default');
    	
    	//add_meta_box('meta_dump', 'Meta Dump', array($this,'all_meta'), 'cards', 'normal', 'default');

	}

	
	function all_meta(){
		
		$getPostCustom=get_post_custom(); // Get all the data 

	    foreach($getPostCustom as $name=>$value) {
	
	        echo "<strong>".$name."</strong>"."  =>  ";
	
	        foreach($value as $nameAr=>$valueAr) {
	                echo "<br />     ";
	                echo $nameAr."  =>  ";
	                echo var_dump($valueAr);
	        }
	
	        echo "<br /><br />";
	
	    }
		
	}
	
	function set_up_gateways(){
	
		$gateway = maybe_unserialize(get_option('options_payment_gateways'));
				
		if($gateway != ''){
		
			include_once(dirname( __FILE__ ) . '/includes/gateways/'.$gateway.'/'.$gateway.'.php');
		
		}
		
	}
	
	function set_up_shipping(){
			
		$shipping = maybe_unserialize(get_option('options_shipping'));
		
		$store_pickup = maybe_unserialize(get_option('options_store_pickup'));
		
		if($shipping != ''){
		
			include_once(dirname( __FILE__ ) . '/includes/shipping/'.$shipping.'/'.$shipping.'.php');
		
		}
		
		if($store_pickup){
			
			include_once(dirname( __FILE__ ) . '/includes/shipping/store_pickup/store_pickup.php');
			
		}
		
	}
	
}

$wordmerce = new wordmerce;

include_once(dirname( __FILE__ ) . '/includes/widgets.php');