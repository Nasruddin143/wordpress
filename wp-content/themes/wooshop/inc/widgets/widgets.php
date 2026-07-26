<?php

require get_template_directory() . '/inc/widgets/class-blog-categories-widget.php';
require get_template_directory() . '/inc/widgets/class-recent-posts-widget.php';
require get_template_directory() . '/inc/widgets/class-tag-cloud-widget.php';
require get_template_directory() . '/inc/widgets/class-image-banner-widget.php';


function wooshop_register_widgets() {

	register_widget( 'WooShop_Blog_Categories_Widget' );
    register_widget( 'WooShop_Recent_Posts_Widget' );
    register_widget( 'WooShop_Tag_Cloud_Widget' );
    register_widget( 'WooShop_Banner_Widget' );

}

add_action( 'widgets_init', 'wooshop_register_widgets' );