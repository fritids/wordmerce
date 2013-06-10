<?php

$gateway = array (
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
	'key' => 'PAYMILL_PRIVATE_KEY',
	'label' => 'Private Key',
	'name' => 'PAYMILL_PRIVATE_KEY',
	'type' => 'text',
	'instructions' => '',
	'id' => 'PAYMILL_PRIVATE_KEY',
	'class' => 'PAYMILL_PRIVATE_KEY',
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