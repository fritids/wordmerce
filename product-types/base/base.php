<?php

$meta_boxes[] = array (
	'id' => 'card_money',
	'title' => 'Card Details',
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
			'instructions' => 'The price of the card',
			'required' => '1',
			'id' => 'price',
			'class' => 'price',
		),
	)
);