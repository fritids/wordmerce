<?php

$gateway = array (
	'key' => 'dialogue_login',
	'label' => 'Login',
	'name' => 'dialogue_login',
	'type' => 'text',
	'instructions' => '',
	'id' => 'dialogue_login',
	'class' => 'dialogue_login',
	'conditional_logic' => 
	array (
		'status' => '1',
		'rules' => 
		array (
			array (
				'field' => 'payment_gateways',
				'operator' => '==',
				'value' => $k,
			),
		),
		'allorany' => 'all',
	),
);

array_push($gateways, $gateway);

$gateway = array (
	'key' => 'dialogue_password',
	'label' => 'Password',
	'name' => 'dialogue_password',
	'type' => 'text',
	'instructions' => '',
	'id' => 'dialogue_password',
	'class' => 'dialogue_password',
	'conditional_logic' => 
	array (
		'status' => '1',
		'rules' => 
		array (
			array (
				'field' => 'payment_gateways',
				'operator' => '==',
				'value' => $k,
			),
		),
		'allorany' => 'all',
	),
);

array_push($gateways, $gateway);

$gateway = array (
	'key' => 'dialogue_brand_name',
	'label' => 'Brand Name',
	'name' => 'dialogue_brand_name',
	'type' => 'text',
	'instructions' => '',
	'id' => 'dialogue_brand_name',
	'class' => 'dialogue_brand_name',
	'conditional_logic' => 
	array (
		'status' => '1',
		'rules' => 
		array (
			array (
				'field' => 'payment_gateways',
				'operator' => '==',
				'value' => $k,
			),
		),
		'allorany' => 'all',
	),
);

array_push($gateways, $gateway);