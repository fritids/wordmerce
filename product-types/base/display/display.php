<?php

//$base_product = new base_product;

class base_product{

	protected $home_page, $slug, $cats, $confirm, $return, $register, $email_replace_tags, $product_types_no, $post_types, $all, $item, $checkout, $cart;

	public function base_product(){
		
		$this->__construct();
		
	}
	
	public function __construct(){
	
		$this->home_page = get_field('shop_page', 'options');
		
		$home_page = get_post($this->home_page);
		
		$this->slug = $home_page->post_name;
		
		$this->home_name = $home_page->post_title;
		
		$this->cats = 'categories';
		
		$this->all = 'all';
				
		$this->confirm = 'confirm';
		
		$this->account = 'account';
		
		$this->checkout = 'checkout';
		
		$this->cart = 'cart';
				
		$this->return = 'return';
		
		$this->item = 'item';
		
		$this->product_types_no = get_option('options_product_types');
		
		$this->post_types = array();
		
		add_option('base_product', array(
			'home_page' => $this->home_page,
			'slug' => $this->slug,
			'cats' => $this->cats,
			'confirm' => $this->confirm,
			'account' => $this->account,
		));
				
		add_action('wp_head', array(&$this, 'enqueue_base_scripts'));
		
		add_filter('query_vars', array($this,'add_query_vars'));
		
		add_filter('rewrite_rules_array', array($this,'add_rewrite_rules'));
		
		add_filter('the_content', array(&$this, 'show_content'));	
		
		add_action('wp_ajax_gateway_notification', array(&$this, 'gateway_notification' ));

		add_action('wp_ajax_nopriv_gateway_notification', array(&$this, 'gateway_notification' ));	
		
		add_filter('email_tags_replace', array(&$this, 'email_tags_replace'), 10, 3);
		
		add_filter('wm_image_src', array(&$this, 'wm_image_src'), 10, 2);
		
		$i = 0; 
		
		while($i < $this->product_types_no){
		
			$name = get_option('options_product_types_'.$i.'_product_name');
			
			$this->post_types[] = preg_replace("/[^a-zA-Z0-9]+/", "", strtolower($name));
			
			$i++;
			
		}
		
	}
	
	public function slug(){
		
		return $this->slug;
		
	}
	
	function add_query_vars($aVars) {
		$aVars[] = "shop_page"; 
		$aVars[] = "item";
		$aVars[] = "TD_id";
		$aVars[] = "type";
		return $aVars;
	}
	
	function add_rewrite_rules($rules) {
	
		if($this->home_page != ''){
	
			$home_page = get_post($this->home_page);
				
			$newrules = array();
			$newrules[$this->slug.'/([^/]+)/?$'] = 'index.php?pagename='.$home_page->post_name.'&type=$matches[1]';
			$newrules[$this->slug.'/([^/]+)/([^/]+)/?$'] = 'index.php?pagename='.$home_page->post_name.'&type=$matches[1]&shop_page=$matches[2]';
			$newrules[$this->slug.'/([^/]+)/([^/]+)/([^/]+)/?$'] = 'index.php?pagename='.$home_page->post_name.'&type=$matches[1]&shop_page=$matches[2]&item=$matches[3]';
			$newrules[$this->slug] = 'index.php?pagename='.$home_page->post_name;
			return $newrules + $rules;
		
		}else{
		
			return $rules;
		
		}
		
	}
	
	function show_content($content){
	
		$return = do_action('wordmerce/before_content');
		
		global $wpdb, $post, $type, $wp_query; 
		
		//echo '<pre>'; print_r($wp_query);
		
		if(isset($wp_query->query_vars['type']) && isset($wp_query->query_vars['shop_page']) && isset($wp_query->query_vars['item'])) {
		
			$return = $this->go_to($wp_query->query_vars['shop_page'], $wp_query->query_vars['type'], $wp_query->query_vars['item']);
		
		}elseif(isset($wp_query->query_vars['type']) && isset($wp_query->query_vars['shop_page'])){

			$return = $this->go_to('', $wp_query->query_vars['type'], $wp_query->query_vars['shop_page']);
			
		}elseif(isset($wp_query->query_vars['type'])){
		
			$return = $this->go_to('', $wp_query->query_vars['type'], '');
			
		}elseif($post->ID == $this->home_page){
			
			$return = 'nope';
			
		}
		
		if($return == 'nope'){
			
			$return = $this->go_home((isset($wp_query->query_vars['type']) ? $wp_query->query_vars['type'] : ''));
			
		}
		
		$return .= do_action('wordmerce/after_content');
		
		$return .= '<div class="clearfix"></div>';
				
		if($return != ''){
			
			echo $return;
		
		}else{
			
			return $content;
			
		}
		
	}
	
	function go_to($type, $page, $item){
	
		$return = '';

		switch($page){
		
			case $this->cats:
		
				if($item != ''){

					$return = $this->show_all_items($item, '', $type);
					
				}else{

					$return = $this->show_all_items('', $type);
				
				}
			
			break;
			
			case $this->item:
			
				if($item != ''){
			
					$return = $this->item_page($item, $type);
				
				}else{
			
					$return = 'nope';
				
				}
			
			break;
			
			case $this->confirm:
			
				$return = $this->confirm_page($item);
			
			break;
			
			case $this->return:
			
				$return = $this->cancelled_page($item);
			
			break;
			
			case $this->account:
			
				$return = $this->account_page();
			
			break;
			
			case $this->cart:
			
				$orders = new orders;
				
				$orders->render_cart();
			
			break;
			
			default:
			
				$return = 'nope';
			
			break;
		
		}
		
		return $return;
		
	}
	
	function go_home($type){

		$type = ($type != '' ? $type : $this->post_types[0]);
		
		$return = $this->show_all_items('', $type);
		
		return $return;
		
	}

	function show_all_items($cat='', $type='', $tax=''){
	
		$type = ($type != '' ? $type : $this->post_types[0]);
	
		$show_widget = get_field('show_widget', 'options'); 
		
		if($show_widget){
			
			$instance = array();
			
			$instance['WM_tax_title'] = 'Categories';
		
			do_action('wordmerce/before_cat_widget');

			the_widget('shop_categories', $instance);
			
			do_action('wordmerce/after_cat_widget');
			
		}

		do_action('wordmerce/allitems', $cat, $type, $tax);
		
		if($show_widget){
		
			do_action('wordmerce/after_products_with_widget');
			
		}
		
	}
	
	function item_page($name, $type){
	
		$type = ($type != '' ? $type : $this->post_types[0]);

		do_action('wordmerce/itempage', $name, $type);
		
	}
			
	function enqueue_base_scripts(){
	
		wp_deregister_script('jquery');
				
		wp_enqueue_script('jquery');
					
		wp_enqueue_style( 'bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
		
		wp_enqueue_style( 'bootstrap-responsive', plugins_url( 'css/bootstrap-responsive.min.css', __FILE__ ) );
		
		$theme = get_field('colour_scheme', 'options');
		
		if($theme != 'Default'){
			
			wp_enqueue_style( 'base-style-theme', plugins_url( 'css/themes/'. $theme .'.css', __FILE__ ) );
			
		}
		
		wp_enqueue_style( 'base-style', plugins_url( 'css/style.css', __FILE__ ) );
		
		wp_enqueue_script( 'simpleCart', plugins_url( 'js/simpleCart.min.js', __FILE__ ) );
		
		wp_enqueue_script( 'spin', plugins_url( 'js/spin.js', __FILE__ ) );
		
		wp_enqueue_script( 'bootstrap', plugins_url( 'js/bootstrap.min.js', __FILE__ ) );
		
		wp_enqueue_script( 'base-plugins', plugins_url( 'js/plugins.js', __FILE__ ) );
		
		wp_enqueue_script( 'base-scripts', plugins_url( 'js/scripts.js', __FILE__ ) );
		
		wp_localize_script( 'base-scripts', 'base_options', array(
			'aja_url' => admin_url( 'admin-ajax.php'), 
			'wordmerce_nonce' => wp_create_nonce( 'wordmerce_nonce' ) ,
			'basket_id' => (isset($_COOKIE["WM_BASKET"]) ? $_COOKIE["WM_BASKET"] : '')
		) );
				
	}
		
	function confirm_page($order_id){
		
		$customer = new customers;
		
		if($customer->is_logged_in()){		
		
			if($customer->order_belongs($order_id)){
		
				$order = new orders;
				
				$verify = $order->verify_purchase($order_id);
				
				if($verify){
					
					echo do_action('wordmerce/confirm/verified', $order_id);
					
					echo do_action('wordmerce/pre_send_confirmation', $order_id);
					
					$order->send_purchase_receipt($order_id);
					
				}else{
					
					echo do_action('wordmerce/confirm/unverified', $order_id);
					
				}
			
			}else{
				
				echo 'We could not find what you were looking for...';
				
			}
			
		}else{
			
			echo $customer->log_user_in(false);
			
		}
		
	}
	
	function cancelled_page($id){
		
		$order = new orders;
		
		if($order->update_status($id, '0')){
			
			echo 'Your order has been cancelled.';
			
		}else{
			
			echo 'Hmmm. Something odd happened there...';
			
		}
		
	}
	
	function gateway_notification(){
			
		echo do_action('wordmerce/notify', $_REQUEST);
		
		die();
		
	}
	
	function checkout(){
		
		
		
	}
	
	function account_page(){
		
		$customer = new customers;
		
		$customer->account_page();
		
	}
	
	function country_select_html($cc='GB'){
		
		$sel = 'selected="selected"';
		
		$return = '
		
    	<select class="cc_select" id="cc_select" name="country">
	        <option value=""></option>
	        <option value="AU"'.($cc=='AU' ? $sel : '').'>Australia</option>
	        <option value="AF"'.($cc=='AF' ? $sel : '').'>Afghanistan</option>
	        <option value="AL"'.($cc=='AL' ? $sel : '').'>Albania</option>
	        <option value="DZ"'.($cc=='DZ' ? $sel : '').'>Algeria</option>
	        <option value="AS"'.($cc=='AS' ? $sel : '').'>American Samoa</option>
	        <option value="AD"'.($cc=='AD' ? $sel : '').'>Andorra</option>
	        <option value="AO"'.($cc=='AO' ? $sel : '').'>Angola</option>
	        <option value="AI"'.($cc=='AI' ? $sel : '').'>Anguilla</option>
	        <option value="AQ"'.($cc=='AQ' ? $sel : '').'>Antarctica</option>
	        <option value="AG"'.($cc=='AG' ? $sel : '').'>Antigua &amp; Barbuda</option>
	        <option value="AR"'.($cc=='AR' ? $sel : '').'>Argentina</option>
	        <option value="AM"'.($cc=='AM' ? $sel : '').'>Armenia</option>
	        <option value="AW"'.($cc=='AW' ? $sel : '').'>Aruba</option>
	        <option value="AT"'.($cc=='AT' ? $sel : '').'>Austria</option>
	        <option value="AZ"'.($cc=='AZ' ? $sel : '').'>Azerbaijan</option>
	        <option value="BS"'.($cc=='BS' ? $sel : '').'>Bahamas</option>
	        <option value="BH"'.($cc=='BH' ? $sel : '').'>Bahrain</option>
	        <option value="BD"'.($cc=='BD' ? $sel : '').'>Bangladesh</option>
	        <option value="BB"'.($cc=='BB' ? $sel : '').'>Barbados</option>
	        <option value="BY"'.($cc=='BY' ? $sel : '').'>Belarus</option>
	        <option value="BE"'.($cc=='BE' ? $sel : '').'>Belgium</option>
	        <option value="BZ"'.($cc=='BZ' ? $sel : '').'>Belize</option>
	        <option value="BJ"'.($cc=='BJ' ? $sel : '').'>Benin</option>
	        <option value="BM"'.($cc=='BM' ? $sel : '').'>Bermuda</option>
	        <option value="BT"'.($cc=='BT' ? $sel : '').'>Bhutan</option>
	        <option value="BO"'.($cc=='BO' ? $sel : '').'>Bolivia</option>
	        <option value="BA"'.($cc=='BA' ? $sel : '').'>Bosnia/Hercegovina</option>
	        <option value="BW"'.($cc=='BW' ? $sel : '').'>Botswana</option>
	        <option value="BV"'.($cc=='BV' ? $sel : '').'>Bouvet Island</option>
	        <option value="BR"'.($cc=='BR' ? $sel : '').'>Brazil</option>
	        <option value="IO"'.($cc=='IO' ? $sel : '').'>British Indian Ocean Territory</option>
	        <option value="BN"'.($cc=='BN' ? $sel : '').'>Brunei Darussalam</option>
	        <option value="BG"'.($cc=='BG' ? $sel : '').'>Bulgaria</option>
	        <option value="BF"'.($cc=='BF' ? $sel : '').'>Burkina Faso</option>
	        <option value="BI"'.($cc=='BI' ? $sel : '').'>Burundi</option>
	        <option value="KH"'.($cc=='KH' ? $sel : '').'>Cambodia</option>
	        <option value="CM"'.($cc=='CM' ? $sel : '').'>Cameroon</option>
	        <option value="CA"'.($cc=='CA' ? $sel : '').'>Canada</option>
	        <option value="CV"'.($cc=='CV' ? $sel : '').'>Cape Verde</option>
	        <option value="KY"'.($cc=='KY' ? $sel : '').'>Cayman Is</option>
	        <option value="CF"'.($cc=='CF' ? $sel : '').'>Central African Republic</option>
	        <option value="TD"'.($cc=='TD' ? $sel : '').'>Chad</option>
	        <option value="CL"'.($cc=='CL' ? $sel : '').'>Chile</option>
	        <option value="CN"'.($cc=='CN' ? $sel : '').'>China, People\'s Republic of</option>
	        <option value="CX"'.($cc=='CX' ? $sel : '').'>Christmas Island</option>
	        <option value="CC"'.($cc=='CC' ? $sel : '').'>Cocos Islands</option>
	        <option value="CO"'.($cc=='CO' ? $sel : '').'>Colombia</option>
	        <option value="KM"'.($cc=='KM' ? $sel : '').'>Comoros</option>
	        <option value="CG"'.($cc=='CG' ? $sel : '').'>Congo</option>
	        <option value="CD"'.($cc=='CD' ? $sel : '').'>Congo, Democratic Republic</option>
	        <option value="CK"'.($cc=='CK' ? $sel : '').'>Cook Islands</option>
	        <option value="CR"'.($cc=='CR' ? $sel : '').'>Costa Rica</option>
	        <option value="CI"'.($cc=='CI' ? $sel : '').'>Cote d\'Ivoire</option>
	        <option value="HR"'.($cc=='HR' ? $sel : '').'>Croatia</option>
	        <option value="CU"'.($cc=='CU' ? $sel : '').'>Cuba</option>
	        <option value="CY"'.($cc=='CY' ? $sel : '').'>Cyprus</option>
	        <option value="CZ"'.($cc=='CZ' ? $sel : '').'>Czech Republic</option>
	        <option value="DK"'.($cc=='DK' ? $sel : '').'>Denmark</option>
	        <option value="DJ"'.($cc=='DJ' ? $sel : '').'>Djibouti</option>
	        <option value="DM"'.($cc=='DM' ? $sel : '').'>Dominica</option>
	        <option value="DO"'.($cc=='DO' ? $sel : '').'>Dominican Republic</option>
	        <option value="TP"'.($cc=='TP' ? $sel : '').'>East Timor</option>
	        <option value="EC"'.($cc=='EC' ? $sel : '').'>Ecuador</option>
	        <option value="EG"'.($cc=='EG' ? $sel : '').'>Egypt</option>
	        <option value="SV"'.($cc=='SV' ? $sel : '').'>El Salvador</option>
	        <option value="GQ"'.($cc=='GQ' ? $sel : '').'>Equatorial Guinea</option>
	        <option value="ER"'.($cc=='ER' ? $sel : '').'>Eritrea</option>
	        <option value="EE"'.($cc=='EE' ? $sel : '').'>Estonia</option>
	        <option value="ET"'.($cc=='ET' ? $sel : '').'>Ethiopia</option>
	        <option value="FK"'.($cc=='FK' ? $sel : '').'>Falkland Islands</option>
	        <option value="FO"'.($cc=='FO' ? $sel : '').'>Faroe Islands</option>
	        <option value="FJ"'.($cc=='FJ' ? $sel : '').'>Fiji</option>
	        <option value="FI"'.($cc=='FI' ? $sel : '').'>Finland</option>
	        <option value="FR"'.($cc=='FR' ? $sel : '').'>France</option>
	        <option value="FX"'.($cc=='FX' ? $sel : '').'>France, Metropolitan</option>
	        <option value="GF"'.($cc=='GF' ? $sel : '').'>French Guiana</option>
	        <option value="PF"'.($cc=='PF' ? $sel : '').'>French Polynesia</option>
	        <option value="TF"'.($cc=='TF' ? $sel : '').'>French South Territories</option>
	        <option value="GA"'.($cc=='GA' ? $sel : '').'>Gabon</option>
	        <option value="GM"'.($cc=='GM' ? $sel : '').'>Gambia</option>
	        <option value="GE"'.($cc=='GE' ? $sel : '').'>Georgia</option>
	        <option value="DE"'.($cc=='DE' ? $sel : '').'>Germany</option>
	        <option value="GH"'.($cc=='GH' ? $sel : '').'>Ghana</option>
	        <option value="GI"'.($cc=='GI' ? $sel : '').'>Gibraltar</option>
	        <option value="GR"'.($cc=='GR' ? $sel : '').'>Greece</option>
	        <option value="GL"'.($cc=='GL' ? $sel : '').'>Greenland</option>
	        <option value="GD"'.($cc=='GD' ? $sel : '').'>Grenada</option>
	        <option value="GP"'.($cc=='GP' ? $sel : '').'>Guadeloupe</option>
	        <option value="GU"'.($cc=='GU' ? $sel : '').'>Guam</option>
	        <option value="GT"'.($cc=='GT' ? $sel : '').'>Guatemala</option>
	        <option value="GN"'.($cc=='GN' ? $sel : '').'>Guinea</option>
	        <option value="GW"'.($cc=='GW' ? $sel : '').'>Guinea-Bissau</option>
	        <option value="GY"'.($cc=='GY' ? $sel : '').'>Guyana</option>
	        <option value="HT"'.($cc=='HT' ? $sel : '').'>Haiti</option>
	        <option value="HM"'.($cc=='HM' ? $sel : '').'>Heard Island And Mcdonald Island</option>
	        <option value="HN"'.($cc=='HN' ? $sel : '').'>Honduras</option>
	        <option value="HK"'.($cc=='HK' ? $sel : '').'>Hong Kong</option>
	        <option value="HU"'.($cc=='HU' ? $sel : '').'>Hungary</option>
	        <option value="IS"'.($cc=='IS' ? $sel : '').'>Iceland</option>
	        <option value="IN"'.($cc=='IN' ? $sel : '').'>India</option>
	        <option value="ID"'.($cc=='ID' ? $sel : '').'>Indonesia</option>
	        <option value="IR"'.($cc=='IR' ? $sel : '').'>Iran</option>
	        <option value="IQ"'.($cc=='IQ' ? $sel : '').'>Iraq</option>
	        <option value="IE"'.($cc=='IE' ? $sel : '').'>Ireland</option>
	        <option value="IL"'.($cc=='IL' ? $sel : '').'>Israel</option>
	        <option value="IT"'.($cc=='IT' ? $sel : '').'>Italy</option>
	        <option value="JM"'.($cc=='JM' ? $sel : '').'>Jamaica</option>
	        <option value="JP"'.($cc=='JP' ? $sel : '').'>Japan</option>
	        <option value="JT"'.($cc=='JT' ? $sel : '').'>Johnston Island</option>
	        <option value="JO"'.($cc=='JO' ? $sel : '').'>Jordan</option>
	        <option value="KZ"'.($cc=='KZ' ? $sel : '').'>Kazakhstan</option>
	        <option value="KE"'.($cc=='KE' ? $sel : '').'>Kenya</option>
	        <option value="KI"'.($cc=='KI' ? $sel : '').'>Kiribati</option>
	        <option value="KP"'.($cc=='KP' ? $sel : '').'>Korea, Democratic Peoples Republic</option>
	        <option value="KR"'.($cc=='KR' ? $sel : '').'>Korea, Republic of</option>
	        <option value="KW"'.($cc=='KW' ? $sel : '').'>Kuwait</option>
	        <option value="KG"'.($cc=='KG' ? $sel : '').'>Kyrgyzstan</option>
	        <option value="LA"'.($cc=='LA' ? $sel : '').'>Lao People\'s Democratic Republic</option>
	        <option value="LV"'.($cc=='LV' ? $sel : '').'>Latvia</option>
	        <option value="LB"'.($cc=='LB' ? $sel : '').'>Lebanon</option>
	        <option value="LS"'.($cc=='LS' ? $sel : '').'>Lesotho</option>
	        <option value="LR"'.($cc=='LR' ? $sel : '').'>Liberia</option>
	        <option value="LY"'.($cc=='LY' ? $sel : '').'>Libyan Arab Jamahiriya</option>
	        <option value="LI"'.($cc=='LI' ? $sel : '').'>Liechtenstein</option>
	        <option value="LT"'.($cc=='LT' ? $sel : '').'>Lithuania</option>
	        <option value="LU"'.($cc=='LU' ? $sel : '').'>Luxembourg</option>
	        <option value="MO"'.($cc=='MO' ? $sel : '').'>Macau</option>
	        <option value="MK"'.($cc=='MK' ? $sel : '').'>Macedonia</option>
	        <option value="MG"'.($cc=='MG' ? $sel : '').'>Madagascar</option>
	        <option value="MW"'.($cc=='MW' ? $sel : '').'>Malawi</option>
	        <option value="MY"'.($cc=='MY' ? $sel : '').'>Malaysia</option>
	        <option value="MV"'.($cc=='MV' ? $sel : '').'>Maldives</option>
	        <option value="ML"'.($cc=='ML' ? $sel : '').'>Mali</option>
	        <option value="MT"'.($cc=='MT' ? $sel : '').'>Malta</option>
	        <option value="MH"'.($cc=='MH' ? $sel : '').'>Marshall Islands</option>
	        <option value="MQ"'.($cc=='MQ' ? $sel : '').'>Martinique</option>
	        <option value="MR"'.($cc=='MR' ? $sel : '').'>Mauritania</option>
	        <option value="MU"'.($cc=='MU' ? $sel : '').'>Mauritius</option>
	        <option value="YT"'.($cc=='YT' ? $sel : '').'>Mayotte</option>
	        <option value="MX"'.($cc=='MX' ? $sel : '').'>Mexico</option>
	        <option value="FM"'.($cc=='FM' ? $sel : '').'>Micronesia</option>
	        <option value="MD"'.($cc=='MD' ? $sel : '').'>Moldavia</option>
	        <option value="MC"'.($cc=='MC' ? $sel : '').'>Monaco</option>
	        <option value="MN"'.($cc=='MN' ? $sel : '').'>Mongolia</option>
	        <option value="MS"'.($cc=='MS' ? $sel : '').'>Montserrat</option>
	        <option value="MA"'.($cc=='MA' ? $sel : '').'>Morocco</option>
	        <option value="MZ"'.($cc=='MZ' ? $sel : '').'>Mozambique</option>
	        <option value="MM"'.($cc=='MM' ? $sel : '').'>Union Of Myanmar</option>
	        <option value="NA"'.($cc=='NA' ? $sel : '').'>Namibia</option>
	        <option value="NR"'.($cc=='NR' ? $sel : '').'>Nauru Island</option>
	        <option value="NP"'.($cc=='NP' ? $sel : '').'>Nepal</option>
	        <option value="NL"'.($cc=='NL' ? $sel : '').'>Netherlands</option>
	        <option value="AN"'.($cc=='AN' ? $sel : '').'>Netherlands Antilles</option>
	        <option value="NC"'.($cc=='NC' ? $sel : '').'>New Caledonia</option>
	        <option value="NZ"'.($cc=='NZ' ? $sel : '').'>New Zealand</option>
	        <option value="NI"'.($cc=='NI' ? $sel : '').'>Nicaragua</option>
	        <option value="NE"'.($cc=='NE' ? $sel : '').'>Niger</option>
	        <option value="NG"'.($cc=='NG' ? $sel : '').'>Nigeria</option>
	        <option value="NU"'.($cc=='NU' ? $sel : '').'>Niue</option>
	        <option value="NF"'.($cc=='NF' ? $sel : '').'>Norfolk Island</option>
	        <option value="MP"'.($cc=='MP' ? $sel : '').'>Mariana Islands, Northern</option>
	        <option value="NO"'.($cc=='NO' ? $sel : '').'>Norway</option>
	        <option value="OM"'.($cc=='OM' ? $sel : '').'>Oman</option>
	        <option value="PK"'.($cc=='PK' ? $sel : '').'>Pakistan</option>
	        <option value="PW"'.($cc=='PW' ? $sel : '').'>Palau Islands</option>
	        <option value="PS"'.($cc=='PS' ? $sel : '').'>Palestine</option>
	        <option value="PA"'.($cc=='PA' ? $sel : '').'>Panama</option>
	        <option value="PG"'.($cc=='PG' ? $sel : '').'>Papua New Guinea</option>
	        <option value="PY"'.($cc=='PY' ? $sel : '').'>Paraguay</option>
	        <option value="PE"'.($cc=='PE' ? $sel : '').'>Peru</option>
	        <option value="PH"'.($cc=='PH' ? $sel : '').'>Philippines</option>
	        <option value="PN"'.($cc=='PN' ? $sel : '').'>Pitcairn</option>
	        <option value="PL"'.($cc=='PL' ? $sel : '').'>Poland</option>
	        <option value="PT"'.($cc=='PT' ? $sel : '').'>Portugal</option>
	        <option value="PR"'.($cc=='PR' ? $sel : '').'>Puerto Rico</option>
	        <option value="QA"'.($cc=='QA' ? $sel : '').'>Qatar</option>
	        <option value="RE"'.($cc=='RE' ? $sel : '').'>Reunion Island</option>
	        <option value="RO"'.($cc=='RO' ? $sel : '').'>Romania</option>
	        <option value="RU"'.($cc=='RU' ? $sel : '').'>Russian Federation</option>
	        <option value="RW"'.($cc=='RW' ? $sel : '').'>Rwanda</option>
	        <option value="WS"'.($cc=='WS' ? $sel : '').'>Samoa</option>
	        <option value="SH"'.($cc=='SH' ? $sel : '').'>St Helena</option>
	        <option value="KN"'.($cc=='KN' ? $sel : '').'>St Kitts &amp; Nevis</option>
	        <option value="LC"'.($cc=='LC' ? $sel : '').'>St Lucia</option>
	        <option value="PM"'.($cc=='PM' ? $sel : '').'>St Pierre &amp; Miquelon</option>
	        <option value="VC"'.($cc=='VC' ? $sel : '').'>St Vincent</option>
	        <option value="SM"'.($cc=='SM' ? $sel : '').'>San Marino</option>
	        <option value="ST"'.($cc=='ST' ? $sel : '').'>Sao Tome &amp; Principe</option>
	        <option value="SA"'.($cc=='SA' ? $sel : '').'>Saudi Arabia</option>
	        <option value="SN"'.($cc=='SN' ? $sel : '').'>Senegal</option>
	        <option value="SC"'.($cc=='SC' ? $sel : '').'>Seychelles</option>
	        <option value="SL"'.($cc=='SL' ? $sel : '').'>Sierra Leone</option>
	        <option value="SG"'.($cc=='SG' ? $sel : '').'>Singapore</option>
	        <option value="SK"'.($cc=='SK' ? $sel : '').'>Slovakia</option>
	        <option value="SI"'.($cc=='SI' ? $sel : '').'>Slovenia</option>
	        <option value="SB"'.($cc=='SB' ? $sel : '').'>Solomon Islands</option>
	        <option value="SO"'.($cc=='SO' ? $sel : '').'>Somalia</option>
	        <option value="ZA"'.($cc=='ZA' ? $sel : '').'>South Africa</option>
	        <option value="GS"'.($cc=='GS' ? $sel : '').'>South Georgia and South Sandwich</option>
	        <option value="ES"'.($cc=='ES' ? $sel : '').'>Spain</option>
	        <option value="LK"'.($cc=='LK' ? $sel : '').'>Sri Lanka</option>
	        <option value="XX"'.($cc=='XX' ? $sel : '').'>Stateless Persons</option>
	        <option value="SD"'.($cc=='SD' ? $sel : '').'>Sudan</option>
	        <option value="SR"'.($cc=='SR' ? $sel : '').'>Suriname</option>
	        <option value="SJ"'.($cc=='SJ' ? $sel : '').'>Svalbard and Jan Mayen</option>
	        <option value="SZ"'.($cc=='SZ' ? $sel : '').'>Swaziland</option>
	        <option value="SE"'.($cc=='SE' ? $sel : '').'>Sweden</option>
	        <option value="CH"'.($cc=='CH' ? $sel : '').'>Switzerland</option>
	        <option value="SY"'.($cc=='SY' ? $sel : '').'>Syrian Arab Republic</option>
	        <option value="TW"'.($cc=='TW' ? $sel : '').'>Taiwan, Republic of China</option>
	        <option value="TJ"'.($cc=='TJ' ? $sel : '').'>Tajikistan</option>
	        <option value="TZ"'.($cc=='TZ' ? $sel : '').'>Tanzania</option>
	        <option value="TH"'.($cc=='TH' ? $sel : '').'>Thailand</option>
	        <option value="TL"'.($cc=='TL' ? $sel : '').'>Timor Leste</option>
	        <option value="TG"'.($cc=='TG' ? $sel : '').'>Togo</option>
	        <option value="TK"'.($cc=='TK' ? $sel : '').'>Tokelau</option>
	        <option value="TO"'.($cc=='TO' ? $sel : '').'>Tonga</option>
	        <option value="TT"'.($cc=='TT' ? $sel : '').'>Trinidad &amp; Tobago</option>
	        <option value="TN"'.($cc=='TN' ? $sel : '').'>Tunisia</option>
	        <option value="TR"'.($cc=='TR' ? $sel : '').'>Turkey</option>
	        <option value="TM"'.($cc=='TM' ? $sel : '').'>Turkmenistan</option>
	        <option value="TC"'.($cc=='TC' ? $sel : '').'>Turks And Caicos Islands</option>
	        <option value="TV"'.($cc=='TV' ? $sel : '').'>Tuvalu</option>
	        <option value="UG"'.($cc=='UG' ? $sel : '').'>Uganda</option>
	        <option value="UA"'.($cc=='UA' ? $sel : '').'>Ukraine</option>
	        <option value="AE"'.($cc=='AE' ? $sel : '').'>United Arab Emirates</option>
	        <option value="GB"'.($cc=='GB' ? $sel : '').'>United Kingdom</option>
	        <option value="UM"'.($cc=='UM' ? $sel : '').'>US Minor Outlying Islands</option>
	        <option value="US"'.($cc=='US' ? $sel : '').'>USA</option>
	        <option value="HV"'.($cc=='HV' ? $sel : '').'>Upper Volta</option>
	        <option value="UY"'.($cc=='UY' ? $sel : '').'>Uruguay</option>
	        <option value="UZ"'.($cc=='UZ' ? $sel : '').'>Uzbekistan</option>
	        <option value="VU"'.($cc=='VU' ? $sel : '').'>Vanuatu</option>
	        <option value="VA"'.($cc=='VA' ? $sel : '').'>Vatican City State</option>
	        <option value="VE"'.($cc=='VE' ? $sel : '').'>Venezuela</option>
	        <option value="VN"'.($cc=='VN' ? $sel : '').'>Vietnam</option>
	        <option value="VG"'.($cc=='VG' ? $sel : '').'>Virgin Islands (British)</option>
	        <option value="VI"'.($cc=='VI' ? $sel : '').'>Virgin Islands (US)</option>
	        <option value="WF"'.($cc=='WF' ? $sel : '').'>Wallis And Futuna Islands</option>
	        <option value="EH"'.($cc=='EH' ? $sel : '').'>Western Sahara</option>
	        <option value="YE"'.($cc=='YE' ? $sel : '').'>Yemen Arab Rep.</option>
	        <option value="YD"'.($cc=='YD' ? $sel : '').'>Yemen Democratic</option>
	        <option value="YU"'.($cc=='YU' ? $sel : '').'>Yugoslavia</option>
	        <option value="ZR"'.($cc=='ZR' ? $sel : '').'>Zaire</option>
	        <option value="ZM"'.($cc=='ZM' ? $sel : '').'>Zambia</option>
	        <option value="ZW"'.($cc=='ZW' ? $sel : '').'>Zimbabwe</option>
		</select>
		
		';
		
		return $return;
		
	}
	
	function email_tags_replace($email, $customer_id, $order_id){
	
		$customer = new customers;
		
		$order = new orders;
	
		$email_replace_tags = array(
			'%%NAME%%' => $customer->get_name($customer_id),
			'%%ITEM_NAME%%' => $order->get_name($order_id),
/* 			'%%ITEM_ID%%' => $order_id, */
			'%%PURCHASE_DATE%%' => get_the_time('l, F j, Y @ G:i', $order_id),
			'%%ORDER_NUMBER%%' => $order_id,
			'%%ORDER_STATUS%%' => $order->get_order_status($order_id),
		);
		
		foreach($email_replace_tags as $tag => $replace){
			
			$email = str_replace($tag, $replace, $email);
			
		}

		return $email;
	}
	
	function wm_image_src($image, $id){
		
		if($image == ''){
			
			$p = get_post($id);
			
			$title = $p->post_title;
			
			return array('http://placehold.it/150x150&text='.$title, '150', '150');
			
		}else{
			
			return $image;
			
		}
		
	}

}