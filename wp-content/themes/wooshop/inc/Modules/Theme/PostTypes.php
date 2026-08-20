<?php
/**
 * WooShop Post Types Module
 *
 * Provides centralized registration support for WooShop theme
 * post types without coupling custom post type definitions to
 * functions.php.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class PostTypes
{
    /**
     * Register the post types module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'init',
            [$this, 'register_post_types']
        );
    }

    /**
     * Register WooShop custom post types.
     *
     * WooCommerce product functionality and product registration
     * remain the responsibility of WooCommerce.
     *
     * Theme-specific post types can be added here when required.
     *
     * @return void
     */
    public function register_post_types(): void
    {
        /*
         * No custom post types are registered by the base
         * WooShop theme at this stage.
         *
         * Feature-specific post types should be registered
         * by their corresponding feature module.
         */
    }
}