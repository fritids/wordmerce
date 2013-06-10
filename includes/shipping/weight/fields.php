<?php

$shipping_method = array (
	'key' => 'PAYMILL_PUBLIC_KEY',
	'label' => 'Public Key',
	'name' => 'PAYMILL_PUBLIC_KEY',
	'type' => 'text',
	'instructions' => '',
	'id' => 'PAYMILL_PUBLIC_KEY',
	'class' => 'PAYMILL_PUBLIC_KEY',
	'conditional_logic' => 
	array (
		'status' => '1',
		'rules' => 
		array (
			array (
				'field' => 'shipping',
				'operator' => '==',
				'value' => $k,
			),
		),
		'allorany' => 'all',
	),
);

array_push($shipping_options, $shipping_method);