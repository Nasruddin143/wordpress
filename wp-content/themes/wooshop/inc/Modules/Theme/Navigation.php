<?php
/**
 * WooShop Navigation Module
 *
 * Handles theme navigation functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Navigation
 */
final class Navigation extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('nav_menu_link_attributes', [$this, 'add_link_attributes'], 10, 4);
    }

    /**
     * Add Bootstrap-compatible navigation attributes.
     *
     * @param array<string, string> $atts Menu link attributes.
     * @param object $item Menu item.
     * @param object $args Menu arguments.
     * @param int $depth Menu depth.
     *
     * @return array<string, string>
     */
    public function add_link_attributes(array $atts, object $item, object $args, int $depth): array
    {
        /*
         * Only modify the primary navigation.
         */
        if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
            return $atts;
        }

        /*
         * Bootstrap nav-link class.
         */
        $existing_class = $atts['class'] ?? '';

        $classes = preg_split('/\s+/', trim($existing_class));

        if (!is_array($classes)) {
            $classes = [];
        }

        if (!in_array('nav-link', $classes, true)) {
            $classes[] = 'nav-link';
        }

        $atts['class'] = trim(
            implode(' ', $classes)
        );

        return $atts;
    }
}