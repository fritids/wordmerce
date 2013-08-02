<?php

$meta_boxes[] = array (
	'id' => 'images',
	'title' => 'Images',
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
			'key' => 'images',
			'label' => 'Images',
			'name' => 'images',
			'type' => 'gallery',
			'instructions' => 'Upload images associated with this product. The image first in the list will be used as the main image.',
			'required' => '1',
			'id' => 'images',
			'class' => 'images',
			'save_format' => 'object',
			'preview_size' => 'thumbnail',
		)
	)
);

$use_stock = get_field('stock_control', 'options');

if($use_stock){

	$meta_boxes[] = array (
		'id' => 'stock',
		'title' => 'Stock Levels',
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
				'key' => 'stock',
				'label' => 'Live Stock',
				'name' => 'stock',
				'type' => 'text',
				'instructions' => 'Enter your current stock figure here. This will decrement with each succesful sale.',
				'required' => '1',
				'id' => 'stock',
				'class' => 'stock',
			),
			array (
				'key' => 'reserved_stock',
				'label' => 'Reserved',
				'name' => 'reserved_stock',
				'type' => 'text',
				'instructions' => 'This is the amount of reserved stock currently being held in live baskets across the site. It will periodically clear itself if the baskets are not checked out by the associated user.',
				'required' => '0',
				'id' => 'reserved_stock',
				'class' => 'reserved_stock',
			)
		)
	);

}