<?php
/**
 * WooShop Jetpack Module
 *
 * Adds Jetpack theme compatibility: infinite scroll,
 * responsive videos, social menus, and content options.
 * Only activates when the Jetpack plugin is active.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Jetpack
 */
final class Jetpack extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        // Bail entirely if Jetpack is not active.
        if (!class_exists('Jetpack')) {
            return;
        }

        add_action('after_setup_theme', [$this, 'setup']);
        add_filter('infinite_scroll_archive_supported', [$this, 'infinite_scroll_supported']);
    }

    /**
     * Declare supported Jetpack features.
     *
     * @return void
     */
    public function setup(): void
    {
        $config   = $this->container->get('config')->get('theme');
        $jetpack  = $config['jetpack'] ?? [];

        /*
         * Infinite Scroll.
         */
        if ($jetpack['infinite_scroll'] ?? true) {
            add_theme_support('infinite-scroll', [
                'container' => 'main',
                'render'    => [$this, 'infinite_scroll_render'],
                'footer'    => 'page',
            ]);
        }

        /*
         * Responsive Videos.
         */
        if ($jetpack['responsive_videos'] ?? true) {
            add_theme_support('jetpack-responsive-videos');
        }

        /*
         * Content Options.
         */
        if ($jetpack['content_options'] ?? false) {
            add_theme_support('jetpack-content-options', [
                'post-details' => [
                    'stylesheet' => 'wooshop-theme',
                    'date'       => true,
                    'categories' => true,
                    'tags'       => true,
                    'author'     => true,
                ],
            ]);
        }
    }

    /**
     * Mark archive pages as supporting infinite scroll.
     *
     * @param bool $supported Current support flag.
     *
     * @return bool
     */
    public function infinite_scroll_supported(bool $supported): bool
    {
        return is_archive() || is_home() || is_search();
    }

    /**
     * Render the post loop for infinite scroll.
     *
     * @return void
     */
    public function infinite_scroll_render(): void
    {
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/content/content', get_post_type());
        }
    }
}