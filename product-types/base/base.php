<?php

$meta_boxes['product_money'] = array (
	'id' => 'product_money',
	'title' => 'Product Price',
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
			'key' => 'price',
			'label' => 'Price',
			'name' => 'price',
			'type' => 'text',
			'instructions' => 'The price of the product',
			'required' => '1',
			'id' => 'price',
			'class' => 'price',
		),
	)
);

$meta_boxes['product_details'] = array (
	'id' => 'product_details',
	'title' => 'Product Details',
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
			'key' => 'description',
			'label' => 'Description',
			'name' => 'description',
			'type' => 'wysiwyg',
			'instructions' => 'A description of the product',
			'required' => '0',
			'id' => 'description',
			'class' => 'description',
			'the_content' => 'yes'
		),
	)
);