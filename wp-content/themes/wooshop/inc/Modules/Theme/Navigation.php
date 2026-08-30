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
     * @param string               $location Menu location slug.
     * @param array<string, mixed> $args     Extra wp_nav_menu() args.
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
            'menu_id'        => 'primary-menu-list',
            'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0 nav-menu nav-menu--' . sanitize_html_class($location),
            'container'      => 'nav',
            'container_class' => false,
            'fallback_cb'    => false,
            'depth'          => 3,
        ], $args));
    }
}