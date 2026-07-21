<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_69319a80604f4',
	'title' => 'Client Testimonial Rating',
	'fields' => array(
		array(
			'key' => 'field_69319a80d61ca',
			'label' => 'Rating',
			'name' => 'rating',
			'aria-label' => '',
			'type' => 'star_rating_field',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'max_stars' => 5,
			'return_type' => 0,
			'allow_half' => 1,
			'theme' => 'default',
			'acfe_settings' => '',
			'acfe_validate' => '',
			'acfe_permissions' => '',
			'allow_in_bindings' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'testimonial',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'display_title' => 'Client Rating',
	'acfe_autosync' => array(
		0 => 'php',
		1 => 'json',
	),
	'acfe_permissions' => '',
	'acfe_form' => 1,
	'acfe_meta' => '',
	'acfe_note' => '',
	'modified' => 1767976820,
));

endif;