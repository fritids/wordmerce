<?php

$weight_based_shipping = new weight_based_shipping;

define('SHIPPING', true);

class weight_based_shipping{
	
	function weight_based_shipping(){
		
		$this->__construct();
		
	}
	
	function __construct(){
		
		add_action('admin_menu', array(&$this, 'settings_pages' ));
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		
		add_action( 'admin_init', array(&$this, 'register_settings' ));
		
		add_filter('wm_base_fields', array($this, 'wm_base_fields' ), 9999999999999999, 2);
		
		add_action('wp_ajax_calculate_shipping', array( &$this, 'calculate_shipping') );

		add_action( 'wp_ajax_nopriv_calculate_shipping', array( &$this, 'calculate_shipping') );
		
	}
	
	function wm_base_fields($meta_boxes, $post_type){

		$meta_boxes['weight'] = array (
			'id' => 'weight',
			'title' => 'Weight('.weight_unit.')',
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
						'value' => $post_type,
						'order_no' => 1,
					),
				),
				'allorany' => 'any',
			),
			'fields' => array(
				array (
					'key' => 'weight',
					'label' => '',
					'name' => 'weight',
					'type' => 'text',
					'instructions' => 'Enter this product\'s weight.',
					'required' => '1',
					'id' => 'weight',
					'class' => 'weight'
				)
			)
		);
				
		return $meta_boxes;		
		
	}
	
	function enqueue_scripts_and_styles(){

		wp_enqueue_script('shipping_weight_js', plugins_url('js/scripts.js',  __FILE__ ), array('simpleCart'));
		
		$data = array(
			'currency_symbol' => currency_symbol,
			'weight_unit' => weight_unit,
			'nonce' => wp_create_nonce( 'nonce' ),
			'admin' => get_bloginfo('url') . '/wp-admin/admin-ajax.php'
		);

		wp_localize_script( 'shipping_weight_js', 'tpc_variables', $data );
		
	}
	
	function register_settings(){
		
		register_setting( 'shipping_options', 'shipping_uk' );

		register_setting( 'shipping_options', 'shipping_eur' );

		register_setting( 'shipping_options', 'shipping_row' );
		
	}
	
	function settings_pages(){
		
		add_submenu_page('acf-options-general', __('Shipping','wordmerce'), __('Shipping','wordmerce'), 'manage_options', 'settings_shipping',  array(&$this, 'add_shipping_page' ));
		
		$shipping_uk = array(
			'100' => '2.75',
			'110' => '2.75',
			'140' => '3.53',
			'250' => '3.12',
			'500' => '3.69',
			'750' => '4.36',
			'1000' => '5.03',
			'1250' => '6.47',
			'1500' => '7.29',
			'1750' => '8.11',
			'1960' => '8.50',
			'2000' => '8.93',
			'3000' => '15.00',
			'4000' => '15.00'
		);

		add_option('shipping_uk', $shipping_uk);

		$shipping_eur = array(
			'10' => '3.27',
			'20' => '3.27',
			'40' => '3.27',
			'60' => '3.27',
			'80' => '3.27',
			'100' => '3.27',
			'120' => '3.53',
			'140' => '3.79',
			'160' => '4.05',
			'180' => '4.31',
			'200' => '4.58',
			'220' => '4.81',
			'240' => '5.06',
			'260' => '5.29',
			'280' => '5.54',
			'300' => '5.79',
			'320' => '6.02',
			'340' => '6.26',
			'360' => '6.49',
			'380' => '6.73',
			'400' => '6.96',
			'420' => '7.20 ',
			'440' => '7.43',
			'460' => '7.67',
			'480' => '7.90',
			'500' => '8.14',
			'520' => '8.37',
			'540' => '8.61',
			'560' => '8.84',
			'580' => '9.08',
			'600' => '9.31',
			'620' =>'9.55',
			'640' =>'9.78',
			'660' =>'10.02',
			'680' =>'10.25',
			'700' =>'10.49',
			'720' =>'10.72',
			'740' =>'10.96',
			'760' =>'11.19',
			'780' =>'11.43',
			'800' =>'11.66',
			'820' =>'11.90',
			'840' =>'12.13',
			'860' =>'12.37',
			'880' =>'12.60',
			'900' =>'12.84',
			'920' =>'13.07',
			'940' =>'13.31',
			'960' =>'13.54',
			'980' =>'13.78',
			'1000' =>'14.01',
			'1020' =>'14.25',
			'1040' =>'14.48',
			'1060' =>'14.72',
			'1080' =>'14.95',
			'1100' =>'15.19',
			'1120' =>'15.42',
			'1140' =>'15.66',
			'1160' =>'15.89',
			'1180' =>'16.13',
			'1200' =>'16.36',
			'1220' =>'16.60',
			'1240' =>'16.83',
			'1260' =>'17.07',
			'1280' =>'17.30',
			'1300' =>'17.54',
			'1320' =>'17.77',
			'1340' =>'18.01',
			'1360' =>'18.24',
			'1380' =>'18.48',
			'1400' =>'18.71',
			'1420' =>'18.95',
			'1440' =>'19.18',
			'1460' =>'19.42',
			'1480' =>'19.65',
			'1500' =>'19.89',
			'1520' =>'20.12',
			'1540' =>'20.36',
			'1560' =>'20.59',
			'1580' =>'20.83',
			'1600' =>'21.06',
			'1620' =>'21.30',
			'1640' =>'21.53',
			'1660' =>'21.77',
			'1680' =>'22.00',
			'1700' =>'22.24',
			'1720' =>'22.47',
			'1740' =>'22.71',
			'1760' =>'22.94',
			'1780' =>'23.18',
			'1800' =>'23.41',
			'1820' =>'23.65',
			'1840' =>'23.88',
			'1860' =>'24.12',
			'1880' =>'24.35',
			'1900' =>'24.59',
			'1920' =>'24.82',
			'1940' =>'25.06',
			'1960' =>'25.29',
			'1980' =>'25.53',
			'2000' =>'25.76',
			'4000' =>'25.76'
		);

		add_option('shipping_eur', $shipping_eur);

		$shipping_row = array(
			'10'=>'3.27',
			'20'=>'3.27',
			'40'=>'3.27',
			'60'=>'3.27',
			'80'=>'3.27',
			'100'=>'3.27',
			'120'=>'3.53',
			'140'=>'3.79',
			'160'=>'4.05',
			'180'=>'4.31',
			'200'=>'4.58',
			'220'=>'4.81',
			'240'=>'5.06',
			'260'=>'5.29',
			'280'=>'5.54',
			'300'=>'5.79',
			'320'=>'6.02',
			'340'=>'6.26',
			'360'=>'6.49',
			'380'=>'6.73',
			'400'=>'6.96',
			'420'=>'7.20',
			'440'=>'7.43',
			'460'=>'7.67',
			'480'=>'7.90',
			'500'=>'8.14',
			'520'=>'8.37',
			'540'=>'8.61',
			'560'=>'8.84',
			'580'=>'9.08',
			'600'=>'9.31',
			'620'=>'9.55',
			'640'=>'9.78',
			'660'=>'10.02',
			'680'=>'10.25',
			'700'=>'10.49',
			'720'=>'10.72',
			'740'=>'10.96',
			'760'=>'11.19',
			'780'=>'11.43',
			'800'=>'11.66',
			'820'=>'11.90',
			'840'=>'12.13',
			'860'=>'12.37',
			'880'=>'12.60',
			'900'=>'12.84',
			'920'=>'13.07',
			'940'=>'13.31',
			'960'=>'13.54',
			'980'=>'13.78',
			'1000'=>'14.01',
			'1020'=>'14.25',
			'1040'=>'14.48',
			'1060'=>'14.72',
			'1080'=>'14.95',
			'1100'=>'15.19',
			'1120'=>'15.42',
			'1140'=>'15.66',
			'1160'=>'15.89',
			'1180'=>'16.13',
			'1200'=>'16.36',
			'1220'=>'16.60',
			'1240'=>'16.83',
			'1260'=>'17.07',
			'1280'=>'17.30',
			'1300'=>'17.54',
			'1320'=>'17.77',
			'1340'=>'18.01',
			'1360'=>'18.24',
			'1360'=>'18.24',
			'1380'=>'18.48',
			'1400'=>'18.71',
			'1420'=>'18.95',
			'1440'=>'19.18',
			'1460'=>'19.42',
			'1480'=>'19.65',
			'1500'=>'19.89',
			'1520'=>'20.12',
			'1540'=>'20.36',
			'1560'=>'20.59',
			'1580'=>'20.83',
			'1600'=>'21.06',
			'1620'=>'21.30',
			'1640'=>'21.53',
			'1660'=>'21.77',
			'1680'=>'22.00',
			'1700'=>'22.24',
			'1720'=>'22.47',
			'1740'=>'22.71',
			'1760'=>'22.94',
			'1780'=>'23.18',
			'1800'=>'23.41',
			'1820'=>'23.65',
			'1840'=>'23.88',
			'1860'=>'24.12',
			'1880'=>'24.35',
			'1900'=>'24.59',
			'1920'=>'24.82',
			'2000'=>'40.00',
			'3000'=>'40.00',
			'4000'=>'40.00'
		);

		add_option('shipping_row', $shipping_row);
				
	}
	
	function add_shipping_page(){ ?>
		
				<div class="wrap">

				<div id="icon-options-general" class="icon32"><br></div>

				<h2>Weight Based Shipping</h2>

				<form method="post" action="options.php">

					<?php settings_fields( 'shipping_options' ); ?>

					<br>

					<table class="widefat post fixed">

						<thead>

						<tr>

							<th>Weight</th>

							<th>Price </th>

							<th>Delete </th>

						</tr>

						</thead>

						<tr id="uk_head">

							<td>
								<h2>UK</h2>
							</td>

							<td>
								<div class="float-left" style="height:25px;">New weight point: </div><input type="text" id="uk_weight_new" class="float-right" />
								<div class="float-left" style="height:25px;">Price: </div><input type="text" id="uk_price_new" class="float-right" />
								<input id="add_uk_price_point" type="button" class="button-primary" value="<?php _e('Add Price Point') ?>" />
							</td>

							<td>
								<input type="submit" class="button-primary float-right" value="<?php _e('Save Changes') ?>" />
							</td>

						</tr>

						<?php $i = 0;

						$total = 0;

						$items = get_option('shipping_uk');

						ksort($items);

						foreach ($items as $k => $v) {

							$i++;

							if (is_int($i/2)) {

								$row = "";

							}else{

								$row = "alternate";

							};

							?>

							<tr class="<?php echo $row; ?>">

								<td id="">

									<?php echo $k . weight_unit; ?>

								</td>

								<td id="">

									<?php echo currency_symbol; ?><input name="shipping_uk[<?php echo $k; ?>]" value="<?php echo $v; ?>" type="text" />

								</td>

								<td><input type="checkbox" class="del_shipping" name="checkbox[]" value="<?php echo $k; ?>"/></td>


							</tr>

						<?php } ?>

						<tr id="eur_head">

							<td>
								<h2>Europe</h2>
							</td>

							<td>
								<div class="float-left" style="height:25px;">New weight point: </div><input type="text" id="eur_weight_new" class="float-right" />
								<div class="float-left" style="height:25px;">Price: </div><input type="text" id="eur_price_new" class="float-right" />
								<input id="add_eur_price_point" type="button" class="button-primary" value="<?php _e('Add Price Point') ?>" />
							</td>

							<td>
								<input type="submit" class="button-primary float-right" value="<?php _e('Save Changes') ?>" />
							</td>

						</tr>

						<?php $i = 0;

						$total = 0;

						$items = get_option('shipping_eur');

						ksort($items);

						foreach ($items as $k => $v) {

							$i++;

							if (is_int($i/2)) {

								$row = "";

							}else{

								$row = "alternate";

							};

							?>

							<tr class="<?php echo $row; ?>">

								<td id="">

									<?php echo $k . weight_unit; ?>

								</td>

								<td id="">

									<?php echo currency_symbol; ?><input name="shipping_eur[<?php echo $k; ?>]" value="<?php echo $v; ?>" type="text" />

								</td>

								<td><input type="checkbox" class="del_shipping" name="checkbox[]" value="<?php echo $k; ?>"/></td>


							</tr>

						<?php } ?>

						<tr id="row_head">

							<td>
								<h2>Rest of World</h2>
							</td>

							<td>
								<div class="float-left" style="height:25px;">New weight point: </div><input type="text" id="row_weight_new" class="float-right" />
								<div class="float-left" style="height:25px;">Price: </div><input type="text" id="row_price_new" class="float-right" />
								<input id="add_row_price_point" type="button" class="button-primary" value="<?php _e('Add Price Point') ?>" />
							</td>

							<td>
								<input type="submit" class="button-primary float-right" value="<?php _e('Save Changes') ?>" />
							</td>

						</tr>

						<?php $i = 0;

						$total = 0;

						$items = get_option('shipping_row');

						ksort($items);

						foreach ($items as $k => $v) {

							$i++;

							if (is_int($i/2)) {

								$row = "";

							}else{

								$row = "alternate";

							};

							?>

							<tr class="<?php echo $row; ?>">

								<td id="">

									<?php echo $k . weight_unit; ?>

								</td>

								<td id="">

									<?php echo currency_symbol; ?><input name="shipping_row[<?php echo $k; ?>]" value="<?php echo $v; ?>" type="text" />

								</td>

								<td><input type="checkbox" class="del_shipping" name="checkbox[]" value="<?php echo $k; ?>"/></td>


							</tr>

						<?php } ?>

					</table>


					<br style="clear:both;"><br style="clear:both;">

					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="shipping_uk, shipping_eur, shipping_row" />

					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>

				</form>

		</div>
		
	<?php }
	
	function calculate_shipping(){

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'nonce' ) )
        	die ( 'Busted!');

		if(isset($_POST['cc']) && $_POST['cc'] != ''){

			$items = $_POST['ids'];

			if($items != ''){

				$w=0;

		        foreach($items as $item){

		        	$w += (get_field('weight',$item['id']) * $item['quantity'] );

		        }

	        }

	        $amount = 0;

	        if($w > 0){

	        	$continent = tpc_countries_continent($_POST['cc']);

	        	if($continent == 'europe'){

		        	if($_POST['cc']=='UK' || $_POST['cc']=='GB'){

			        	// UK SHIPPING
			        	$shipping = get_option('shipping_uk');
			        	foreach($shipping as $k => $v){

			        		if($k >= $w){
			        			$amount = $v;
			        			break;
			        		}

			        	}

					}else{

						// EUR SHIPPING
						$shipping = get_option('shipping_eur');
			        	foreach($shipping as $k => $v){

			        		if($k >= $w){
			        			$amount = $v;
			        			break;
			        		}

			        	}
					}

				}else{

					// ROW SHIPPING
					$shipping = get_option('shipping_row');
		        	foreach($shipping as $k => $v){

		        		if($k >= $w){
		        			$amount = $v;
		        			break;
		        		}

		        	}

				}

			}
			
			if(isset($_COOKIE['WM_BASKET']) && strlen($_COOKIE['WM_BASKET']) > 0){
							
				$order = new orders;
				
				$order->update_data_on_order($_COOKIE['WM_BASKET'], 'shipping', number_format($amount, 2, '.', ','));
				
			}

        	echo number_format($amount, 2, '.', ',');

        }

        die();

	}
	
}