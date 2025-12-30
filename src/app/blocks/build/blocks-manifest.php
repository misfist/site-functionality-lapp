<?php
// This file is generated. Do not modify it manually.
return array(
	'cta-slot' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'site-functionality/cta-slot',
		'version' => '0.1.0',
		'title' => 'Global CTA Slot',
		'category' => 'lapp',
		'icon' => 'format-image',
		'description' => 'Displays the globally configured CTA.',
		'attributes' => array(
			'anchor' => array(
				'type' => 'string'
			)
		),
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'align' => array(
				'wide',
				'full',
				'center',
				'left',
				'right'
			),
			'color' => array(
				'background' => true
			)
		),
		'textdomain' => 'site-functionality',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'sponsor' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'site-functionality/sponsor',
		'version' => '0.1.0',
		'title' => 'Sponsor',
		'category' => 'lapp',
		'icon' => 'money-alt',
		'description' => 'Display sponsor.',
		'attributes' => array(
			'objectId' => array(
				'type' => 'string',
				'default' => '0'
			),
			'objectType' => array(
				'type' => 'string'
			)
		),
		'usesContext' => array(
			'postId',
			'termId'
		),
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'align' => true,
			'customClassName' => true
		),
		'textdomain' => 'site-functionality',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
