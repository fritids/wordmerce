<?php

$meta_boxes[] = array (
	'id' => 'card',
	'title' => 'The Card',
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
			'key' => 'the_design',
			'label' => 'The Design',
			'name' => 'the_design',
			'type' => 'image',
			'instructions' => 'Upload the design here',
			'required' => '1',
			'id' => 'the_design',
			'class' => 'the_design',
			'save_format' => 'object',
			'preview_size' => 'thumbnail',
		),
		array (
			'key' => 'description',
			'label' => 'Description',
			'name' => 'description',
			'type' => 'wysiwyg',
			'instructions' => 'A description of the card',
			'required' => '0',
			'id' => 'description',
			'class' => 'description',
		),
		array (
			'key' => 'designer',
			'label' => 'Designer',
			'name' => 'designer',
			'type' => 'text',
			'instructions' => 'The name of the designer',
			'required' => '0',
			'id' => 'designer',
			'class' => 'designer',
		),
		array (
			'key' => 'designer_link',
			'label' => 'Designer Link',
			'name' => 'designer_link',
			'type' => 'text',
			'instructions' => 'A full link to the designers website',
			'required' => '0',
			'id' => 'designer_link',
			'class' => 'designer_link',
		),
	)
);