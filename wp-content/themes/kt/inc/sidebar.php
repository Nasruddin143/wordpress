<?php

/**
 * -----------------------------------------------------------------------------
 * WIDGET AREAS
 * -----------------------------------------------------------------------------
 */
function kt_widgets_init()
{
	register_sidebar([
		'name' => __('Blog Sidebar', 'kt'),
		'id' => 'sidebar-1',
		'description' => __('Widgets in this area will be shown on all posts and pages.', 'kt'),
		'before_widget' => '<li id="%1$s" class="mb-4 widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle h5 fw-bold">',
		'after_title' => '</h2>',
		'before_sidebar' => '<ul class="list-unstyled">',
		'after_sidebar' => '</ul>',
		'show_in_rest' => false,
	]);
}
add_action('widgets_init', 'kt_widgets_init');

// Disable Block-Widgets
add_filter('use_widgets_block_editor', '__return_false');