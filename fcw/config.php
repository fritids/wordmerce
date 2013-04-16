<?php

/*
** 					Current available modules/submodules	 				**
**																			**
** 						wordpress							 				**
** 							customposttype					 				**
**							customtaxonomy									**
** 							custommetabox					 				**
**							mysupermarket									**
**							shortcode										**
**							settingspage									**
**						mysqlconnection										**
**						facebook_connect									**
**						ajax												**
**						bxslider											**
**						image												**
**																			**
**																			**
*/

$config = array(
	'modules' => array(
		'wordpress'=>array(
			'customposttype',
			'custommetabox',
			'settingspage',
			'customtaxonomy',
		),
		'image',
		'mobpay',
		'esendex'
	),
	'paths' => array(
		'url' => 'http://wp.betadev.co/textcards/wp-content/plugins/wordmerce/',
		//'fcw_path' => 'fcw'
	),
	'acf-activation-codes' => array(
		'repeater' => 'QJF7-L4IX-UCNP-RF2W',
		'options_page' => 'OPN8-FA4J-Y2LW-81LS',
	    'flexible_content' => 'FC9O-H6VN-E4CL-LT33',
	    'gallery' => 'GF72-8ME6-JS15-3PZC'
	),
	'mobpay' => array(
		'login' => 'waptrial2@waptrial2',
		'password' => 'qeLp21Md',
		'BrandName' => 'Dialogue Test'
	),
	'esendex' => array(
		'account_number' => 'EX0104288',
		'username' => 'darren@textcards.com',
		'password' => 'welcome'
	)
);

?>