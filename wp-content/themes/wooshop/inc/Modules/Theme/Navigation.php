<?php
/**
 * WooShop Navigation Module
 *
 * Registers nav menus (from theme.php config) and
 * provides a reusable render helper used by template parts.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WP_Post;

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
        // Nav menus are registered in Setup via theme.php.
        // This module owns runtime rendering and Walker injection.
        add_filter('wp_nav_menu_args', [$this, 'inject_walker']);

        add_filter('nav_menu_link_attributes', [$this, 'add_link_attributes'], 10, 4);
    }

    /**
     * Inject a custom Walker into every nav menu call
     * that does not already specify one.
     *
     * @param array<string, mixed> $args wp_nav_menu() arguments.
     *
     * @return array<string, mixed>
     */
    public function inject_walker(array $args): array
    {
        if (empty($args['walker']) && class_exists(NavWalker::class)) {
            $args['walker'] = new NavWalker();
        }

        return $args;
    }

    /**
     * Render a navigation menu by location.
     *
     * Use this static helper from template parts:
     *
     *   \WooShop\Modules\Theme\Navigation::render('primary');
     *
     * @param string $location Menu location slug.
     * @param array<string, mixed> $args Extra wp_nav_menu() args.
     *
     * @return void
     */
    public static function render(string $location, array $args = []): void
    {
        if (!has_nav_menu($location)) {
            return;
        }

        wp_nav_menu(array_merge([
            'theme_location' => $location,
            'menu_id' => 'primary-menu-list',
            'menu_class' => 'navbar-nav me-auto mb-2 mb-lg-0 nav-menu nav-menu--' . sanitize_html_class($location),
            'container' => 'nav',
            'container_class' => false,
            'fallback_cb' => false,
            'depth' => 3,
        ], $args));
    }

    /**
     * Add Bootstrap navigation classes.
     *
     * @param array<string, mixed> $atts Menu link attributes.
     * @param WP_Post $item Menu item.
     * @param object $args Menu arguments.
     * @param int $depth Menu depth.
     *
     * @return array<string, mixed>
     */
    public function add_link_attributes(array $atts, WP_Post $item, object $args, int $depth): array
    {
        if (!isset($args->theme_location)) {
            return $atts;
        }

        $locations = ['primary', 'footer',];

        if (!in_array($args->theme_location, $locations, true)) {
            return $atts;
        }

        $existing_class = $atts['class'] ?? '';

        $classes = preg_split('/\s+/', trim((string)$existing_class));

        if (!is_array($classes)) {
            $classes = [];
        }

        if (!in_array('nav-link', $classes, true)) {
            $classes[] = 'nav-link';
        }

        $atts['class'] = trim(implode(' ', $classes));

        return $atts;
    }
}