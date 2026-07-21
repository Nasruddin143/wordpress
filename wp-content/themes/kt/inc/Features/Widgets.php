<?php

namespace KT\Features;

defined('ABSPATH') || exit;

class Widgets
{
    public function __construct()
    {
        add_action('widgets_init', [$this, 'register_sidebars']);
        // add_filter('use_widgets_block_editor', '__return_false'); // optional
    }

    /**
     * ----------------------------------------
     * REGISTER SIDEBARS
     * ----------------------------------------
     */
    public function register_sidebars()
    {
        $this->register([
            'name'        => __('Blog Sidebar', 'kt'),
            'id'          => 'sidebar-1',
            'description' => __('Widgets in this area will be shown on all posts and pages.', 'kt'),
        ]);
    }

    /**
     * ----------------------------------------
     * GENERIC SIDEBAR REGISTER (REUSABLE)
     * ----------------------------------------
     */
    private function register($args = [])
    {
        $defaults = [
            'name'            => '',
            'id'              => '',
            'description'     => '',
            'before_widget'   => '<li id="%1$s" class="mb-4 widget %2$s">',
            'after_widget'    => '</li>',
            'before_title'    => '<h2 class="widgettitle h5 fw-bold">',
            'after_title'     => '</h2>',
            'before_sidebar'  => '<ul class="list-unstyled">',
            'after_sidebar'   => '</ul>',
            'show_in_rest'    => false,
        ];

        register_sidebar(wp_parse_args($args, $defaults));
    }
}
