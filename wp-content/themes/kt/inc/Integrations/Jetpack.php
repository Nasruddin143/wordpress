<?php
namespace KT\Integrations;

defined('ABSPATH') || exit;

class Jetpack {

    public function __construct() {

        add_action('after_setup_theme', [$this, 'setup']);

        // Performance optimizations
        add_filter('jetpack_should_load_frontend_scripts', '__return_false');
        add_filter('jetpack_implode_frontend_css', '__return_false');
        add_filter('jetpack_lazy_images_enabled', '__return_false');
        add_filter('jetpack_enable_open_graph', '__return_false');
        add_filter('jetpack_enable_seo_tools', '__return_false');
    }

    /**
     * ----------------------------------------
     * Jetpack Setup
     * ----------------------------------------
     */
    public function setup() {

        $this->infinite_scroll();
        $this->responsive_videos();
        $this->content_options();
    }

    /**
     * ----------------------------------------
     * Infinite Scroll
     * ----------------------------------------
     */
    private function infinite_scroll() {

        // SEO safe: disable on mobile
        if (wp_is_mobile()) {
            return;
        }

        add_theme_support('infinite-scroll', [
            'container'       => 'main',
            'render'          => [$this, 'render_posts'],
            'footer'          => 'page',
            'posts_per_page'  => get_option('posts_per_page'),
        ]);
    }

    /**
     * ----------------------------------------
     * Render Posts (Infinite Scroll)
     * ----------------------------------------
     */
    public function render_posts() {

        while (have_posts()) {
            the_post();

            if (is_search()) {
                get_template_part('template-parts/content', 'search');
            } else {
                get_template_part('template-parts/content', get_post_type());
            }
        }
    }

    /**
     * ----------------------------------------
     * Responsive Videos
     * ----------------------------------------
     */
    private function responsive_videos() {
        add_theme_support('jetpack-responsive-videos');
    }

    /**
     * ----------------------------------------
     * Content Options
     * ----------------------------------------
     */
    private function content_options() {

        add_theme_support('jetpack-content-options', [
            'post-details' => [
                'stylesheet' => 'kt-style',
                'date'       => '.posted-on',
                'categories' => '.cat-links',
                'tags'       => '.tags-links',
                'author'     => '.byline',
                'comment'    => '.comments-link',
            ],
            'featured-images' => [
                'archive' => true,
                'post'    => true,
                'page'    => true,
            ],
        ]);
    }
}