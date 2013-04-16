<?php

$cards = new cards;

class cards extends base_product{

	private $card, $initial_text, $sample_text, $gateway_name;

	function textcards(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		parent::__construct();
				
		$this->item = 'card';
						
		$this->initial_text = 'Your text will go here.<br>Drag to position me.';
		
		$this->sample_text = 'textcards.com';
		
		$this->gateway_name = 'PayForIt - SMS';
		
		add_option('cards', array(
			'item' => $this->item,
			'initial_text' => $this->initial_text,
			'sample_text' => $this->sample_text,
			'gateway_name' => $this->gateway_name,
		));

		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
						
		add_action('wordmerce/itempage', array(&$this, 'card_page'), 10, 1 );
		
		add_action('wordmerce/allitems', array(&$this, 'show_all_cards'), 10, 1  );
		
		add_action('wordmerce/confirm/verified', array(&$this, 'confirm_order' ), 10, 1);
		
		add_action('wordmerce/confirm/unverified', array(&$this, 'unconfirmed_order' ), 10, 1);
		
		add_action('wordmerce/pre_send_confirmation', array(&$this, 'send_confirmation' ), 10, 1);
		
		add_action('wp_ajax_create_image', array(&$this, 'create_image' ));

		add_action('wp_ajax_nopriv_create_image', array(&$this, 'create_image' ));
		
		add_action('wp_ajax_add_text_to_image', array(&$this, 'add_text_to_image_ajax' ));

		add_action('wp_ajax_nopriv_add_text_to_image', array(&$this, 'add_text_to_image_ajax' ));
		
		add_action('wp_ajax_buy_card', array(&$this, 'buy_card_ajax' ));

		add_action('wp_ajax_nopriv_buy_card', array(&$this, 'buy_card_ajax' ));
		
		add_action('wp_ajax_verify_phone_number', array(&$this, 'verify_phone_number' ));

		add_action('wp_ajax_nopriv_verify_phone_number', array(&$this, 'verify_phone_number' ));
		
		add_action('wordmerce/before_cat_widget', array(&$this, 'before_cat_widget' ));
		
		add_action('wordmerce/after_cat_widget', array(&$this, 'after_cat_widget' ));
		
		add_action('wordmerce/after_products_with_widget', array(&$this, 'after_products_with_widget' ));
		
	}

	function show_all_cards($cat=''){

		global $wpdb, $post;
		
		if($cat == ''){
			
			$args = array( 'numberposts' => -1, 'post_type' => 'cards');
			
		}else{
			
			$args = array( 'numberposts' => -1, 'post_type' => 'cards', 'card_categories' => $cat);	
			
		}
		
		$return = '';
		
		if($cat != ''){
		
			$category = get_term_by('slug', $cat, 'card_categories');
		
			$return .= '<h2>' . $category->name . '</h2>';
		
		}
		
		$cards = get_posts( $args );
		
		if($cards){
		
			foreach( $cards as $post ) :	setup_postdata($post);
							
				$term_list = wp_get_post_terms($post->ID, 'card_categories', array("fields" => "all"));

				$price = get_field('price', $post->ID);
				
				$class = "";
				
				foreach($term_list as $term){
					
					$class .= $term->slug." ";
					
				}
				
				$image_attributes = wp_get_attachment_image_src( get_field('the_design'), 'thumbnail' ); 
				
				$return .= '<div class="item_container">';
	 
					$return .= '<a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->item.'/'.$post->post_name.'" id="'.$post->post_name.'">
						<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" class="'.$class .' design" alt="'.$post->post_title.'">';
					
						$return .= '<h4 class="item_title">'.$post->post_title.'</h4>';
					
						$return .= '<p class="item_price">&pound;'.$price.'</p>';
						
					$return .= '</a>';
						
					if(count($term_list) > 0){
					
						$return .= '<p class="terms">In: ';
						
						$ti = 0;
													
						foreach ($term_list as $term){
					
							if($ti == 0){
								$start = '';
							}else{
								$start = ', ';
							}
					
							$return .= $start . '<a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->cats.'/'.$term->slug.'">'.$term->name.'</a>';
							
							$ti++;
					
						}
						
						$return .= '</p>';
					
					}
				
				$return .= '</div>';
							
			endforeach; 
		
		}else{
			
			$return .= '<p>Sorry, but there are no cards in this category</p>';
			
		}
		
		echo $return;
		
	}
	
	function card_page($card_name){ 
		
		global $image_and_font;
		
		$card = get_page_by_path($card_name, 'OBJECT', 'cards');
		
		//echo '<pre>'; print_r($card);
		
		$watermarked = get_field('the_watermarked_design', $card->ID);
		
		$title = $card->post_title;
				
		$price = get_field('price', $card->ID);
				
		$designer_name = get_field('designer', $card->ID);
		
		$designer_link = get_field('designer_link', $card->ID);
		
		$description = get_field('description', $card->ID);
				
		if($watermarked == ''){
			
			$design = get_field('the_design', $card->ID);
			
			$watermarked_image = $this->add_text_to_image($design, $this->sample_text, array('left' => '0', 'top' => '100', 'center' => 'yes', 'color' => 'red', 'font_size' => '35', 'angle' => '0'));
			
			$image = getimagesize($watermarked_image); 
			
		}else{
			
			$watermarked_image = wp_get_attachment_url($watermarked);
			
			$image = getimagesize($design);
			
		}
		
		$return .= '<div id="image_wrapper">';
				
			$return .= '<div style="width:'.$image[0].'px; height:'.$image[1].'px;" id="the_image">';
			
				$return .= '<img rel="'.$design.'" id="preview_image" src="'.$watermarked_image.'" />';
				
			$return .= '</div>';
			
		$return .= '</div>';
			
		$return .= '<div id="card_meta">';
		
			$return .= '<h1>'.$title.'</h1>';
			
			$return .= '<p class="info">';
			
			if($designer_name != ''){
				
				if($designer_link != ''){
					
					$return .= '<a href="'.$designer_link.'" target="_blank">'.$designer_name.'</a>';
					
				}else{
					
					$return .= $designer_name;
					
				}
				
			}
			
			$return .= '</p>';
			
			$return .= '<p class="price">&pound;'.$price.'</p>';
			
			$return .= '<a class="button edit_button" href="#">Customise</a>';
			
			$return .= '<a class="button buy_now" href="#">Buy Now</a>';
			
			$return .= '<div id="confirmation">';
			
				$return .= '<p>Please review this preview of your card and click "confirm" to confirm that it is exactly as you would like it. Once purchased, your card can not be altered. The final card will not contain the watermark.</p>';
				
				$return .= '<img id="final_preview" src="'.plugins_url( 'img/loading.gif', __FILE__ ).'" /><br><span id="loading_status"></span><br><br>';
				
				$return .= '<div id="payment"></div>';
				
				//$return .= '<label for="country">Country of recipient: </label>';
				
				//$return .= $this->country_select_html('GB');
				
				$return .= '<label for="send_to">Send card to: </label><input name="send_to" type=text" id="send_to" />';
				
				$return .= '<p>Valid mobile phone number only</p>';
				
				$return .= '<a class="button confirm" href="'.$card->ID.'">Confirm</a>';
				
				$return .= '<div class="or"> - or - </div>';
				
				$return .= '<a class="button edit_button" href="#">Customise more</a>';
						
			$return .= '</div>';
			
			$return .= '<div id="customisation_options">';
			
				$return .= '<h3>Your Text</h3>';
			
				$return .= '<textarea id="your_text">'.str_replace("<br>", "\n", $this->initial_text).'</textarea>';
				
				$return .= '<h3>Font Family</h3>';
			
				$return .= $image_and_font->all_fonts_select();
				
				$return .= '<h3>Font Size</h3>';
			
				$return .= '<div id="font_size_slider"></div>';
				
				$return .= '<h3>Font Colour</h3>';
			
				$return .= '<input type="text" value="#bada55" class="my-color-field" />';
				
				$return .= '<a href="#" class="button" id="preview_link">Preview</a>';
				
				$return .= '<img src="'.plugins_url( 'img/loading.gif', __FILE__ ).'" id="loading_preview" />';
				
				$return .= '<div id="preview_rendered_image"></div>';
			
			$return .= '</div>';
		
		$return .= '</div>';	
		
		$return .= '<div id="main_desc">';
		
			$return .= $description;
		
		$return .= '</div>';	
		
		echo $return;
		
	}
	
	function categories_list(){
		
		$term_list_all = get_terms('card_categories', array("hide_empty" => false));
		
		$return = '';
				
		$return .= '<ul id="textcards_cat_list">';
		
		$return .=  '<li><a href="'.get_bloginfo('url').'/'.$this->slug.'" id="all">All</a></li>';
		
		foreach($term_list_all as $term){
			
			$return .=  '<li><a href="'.get_bloginfo('url').'/'.$this->slug.'/'.$this->cats.'/'.$term->slug.'" id="'.$term->slug.'">'.$term->name.'</a></li>';
			
		}
		
		$return .=  '</ul>';
		
		return $return;
		
	}
	
	function add_text_to_image($id='', $text='', $options='', $sec_text='', $save_path = ''){
	
		global $image_and_font;
		
		if(isset($options['color'])){

			$colors = $image_and_font->color_name_to_rgb($options['color']);
		
		}else{

			$colors['r'] = $options['r'];
			
			$colors['g'] = $options['g'];
			
			$colors['b'] = $options['b'];
		}
		
		$url = admin_url( 'admin-ajax.php?action=create_image&id='.$id.'&text='.urlencode($text).'&sec_text='.urlencode($sec_text).'&top='.$options['top'].'&left='.$options['left'].'&r='.$colors['r'].'&g='.$colors['g'].'&b='.$colors['b'].'&font_size='.$options['font_size'].'&angle='.$options['angle'].'&center='.$options['center'].'&width='.(isset($options['width']) ? $options['width'] : '').'&height='.(isset($options['height']) ? $options['height'] : '').'&font='.urlencode($options['font']) );
					
		$im = file_get_contents($url);
		
		if($save_path != ''){
		
			file_put_contents($save_path, $im);
						
		}
		
		return 'data:image/png;base64,'.base64_encode($im);
		
	}
	
	function add_text_to_image_ajax($id='', $text='', $options='', $sec_text=''){
	
		if($id == ''){
			$id = $_REQUEST['id'];
		}
		
		if($text == ''){
			$text = $_REQUEST['text'];
		}
		
		if($sec_text == ''){
			$sec_text = $_REQUEST['sec_text'];
		}
		
		if($options == ''){
			$options = $_REQUEST;
		}
		
		if($save_path == ''){
			$save_path = $_REQUEST['save_path'];
		}
		
		echo $this->add_text_to_image($id, $text, $options, $sec_text, $save_path);
		
		die();
		
	}
	
	function create_image(){
	
		global $wpdb, $image_and_font;
				
		$upload_dir = wp_upload_dir();
	
		$image = wp_get_attachment_metadata($_REQUEST['id']); 
		
		$image_and_font->create_image($upload_dir['basedir'].'/'.$image['file'], $_REQUEST['text'], $_REQUEST, (isset($_REQUEST['font']) ? $_REQUEST['font'] : ''), (isset($_REQUEST['sec_text']) ? $_REQUEST['sec_text'] : ''));
		
		die();

	}
		
	function enqueue_scripts(){
	
		global $image_and_font;
		
		echo $image_and_font->css_link();
		
		wp_enqueue_style( 'texcards-plugins-style', plugins_url( 'css/plugins.css', __FILE__ ) );
		
		wp_enqueue_style( 'texcards-style', plugins_url( 'css/style.css', __FILE__ ) );
		
		wp_enqueue_script( 'jquery' );
		
		wp_enqueue_script( 'jquery-ui' );
		
		wp_enqueue_style('jquery_ui_css', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css');
		
		wp_enqueue_script( 'jquery-ui-draggable' );
		
		wp_enqueue_script( 'jquery-ui-widget' );
		
		wp_enqueue_script( 'jquery-ui-mouse' );
		
		wp_enqueue_script( 'jquery-ui-slider' );
		
		wp_enqueue_script( 'texcards-plugins', plugins_url( 'js/plugins.js', __FILE__ ), array('jquery-ui-widget', 'jquery-ui-mouse'));
		
		wp_enqueue_script( 'texcards-scripts', plugins_url( 'js/scripts.js', __FILE__ ));
		
		wp_localize_script( 'texcards-scripts', 'options', array('initial_text' => $this->initial_text, 'aja_url' => admin_url( 'admin-ajax.php'), 'sample_text' => $this->sample_text, 'loading_img' => plugins_url( 'img/loading.gif', __FILE__ ) ) );
		
	}
	
	function buy_card_ajax(){
			
		$card = get_post( $_POST['card_id'] );
		
		$order = new orders;
		
		$user = (isset($_POST['user']) ? $_POST['user'] : '');
		
		$order_id = $order->add_order($_POST['card_id'], $_POST, '1', $user);
		
		if($order_id){
			
			$order->add_data_to_order($order_id, 'phone_number', $_POST['number']);
			
			$order->add_data_to_order($order_id, 'product_id', $_POST['card_id']);
	
			$price = get_field('price', $_POST['card_id']);
			
			$title = $card->post_title;
		
			$args = array(
				'Category' => 'BUSINESS_SERVICES',
				'Adult' => false,
				'CurrencyCode' => 'GBP',
				'MonetaryValue' => $price,
				'ProductCode' => $_POST['card_id'],
				'ProductDescription' => $title,
				'ReturnUrl' => get_bloginfo('url').'/'.$this->slug.'/'.$this->return.'/'.$order_id,
				'FulfilmentUrl' => get_bloginfo('url').'/'.$this->slug.'/'.$this->confirm.'/'.$order_id,
				'ReceiptSms' => false,
				'ServiceDeliveryMessage' => 'Send your text card now.'
			);
			
			$creds = array(
				'login'=> get_option('options_dialogue_login', true),
				'password' => get_option('options_dialogue_password', true),
				'BrandName' => get_option('options_dialogue_brand_name', true),
			);
			
			$gateway_dialogue = new gateway_dialogue($creds);
			
			$response = $gateway_dialogue->one_off_purchase($args);
			
			$order->update_status($order_id, '1.5');
			
			$order->set_gateway($order_id, $this->gateway_name);
			
			$order->add_gateway_transaction_id($order_id, $response->PaymentId);
			
			echo $response->RedirectUrl;
		
		}else{
			
			echo 'error';
			
		}
		
		die();
		
	}
	
	function confirm_order($order_id){
	
		$o = new orders;
	
		$sent_id = $o->get_data($order_id, 'esendex_sent_id');
		
		if($sent_id == ''){
		
			$order = $o->get_order($order_id);
			
			$data = $o->get_order_data($order_id);
		
			$uploads = wp_upload_dir();
			
			$img_path = '/' . $order_id . '.png';
		
			$this->add_text_to_image($data['id'] , $data['text'], $data, '', $uploads['path'].$img_path);
			
			$o->add_data_to_order($order_id, 'image_path',$uploads['url'].$img_path);
		
		}
		
		$customer_id = $o->get_customer_id($order_id);
		
		$customer = new customers;
				
		echo '<h2>Thank you '.$customer->get_name($customer_id).'!</h2>';
		
		echo '<img style="float: right; margin: 20px;" src="'.$o->get_data($order_id, 'image_path').'">';
		
	}
	
	function unconfirmed_order($order_id){
		
		echo '<p>Your payment has not yet been received.</p> <p>This is normal as there can sometimes be a delay from the mobile operator.</p><You will receive an email when your order has been processed.</p>';
		
	}
	
	function send_confirmation($order_id){
		
		$o = new orders;
		
		$order = $o->get_order($order_id);
		
		$data = $o->get_order_data($order_id);
		
		$image = $o->get_data($order_id, 'image_path');
		
		$number = preg_replace("/^0/", "44", $data['number']);
				
		$message = 'Hi, you have been sent a card from textcards.com, please click the link to view it. '. $image;
		
		$esendex = new esendex;
		
		$sent_id = $o->get_data($order_id, 'esendex_sent_id');
		
		if($sent_id == ''){

			$response = $esendex->send_sms('Text Cards', $number, $message);
			
			$o->add_data_to_order($order_id, 'esendex_sent_id', $response->id);
			
			$o->add_data_to_order($order_id, 'date_sent', date('l jS \of F Y h:i:s A'));
			
			echo '<p>Thank you for your order. Your card is being sent to '.$data['number'].'</p>';
			
		}else{
		
			$status = $esendex->check_sms_status($sent_id);
			
			if($status == ''){
						
				$o->add_data_to_order($order_id, 'esendex_status', $status);
			
			}
			
			$date_sent = $o->get_data($order_id, 'date_sent');
			
			echo '<p>Thank you for your order. Your card was sent to '.$data['number'] . ' on ' .$date_sent .'.</p>';
			
		}
		
	}
	
	function before_cat_widget(){
		
		echo '<div class="cards_sidebar">';
		
	}
	
	function after_cat_widget(){
		
		echo '</div>
		
		<div class="items_with_sidebar">';
		
	}
	
	function after_products_with_widget(){
		
		echo '</div>';
		
	}
	
}