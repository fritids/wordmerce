<?php

$gateway = array (
	'key' => 'Paypal',
	'label' => 'Paypal test',
	'name' => 'Paypal',
	'type' => 'textarea',
	'instructions' => '',
	'id' => 'Paypal',
	'class' => 'Paypal',
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