<?php
/**
 * WordPress Post Types.
 *
 * Provides a centralized class for registering WooShop custom post types.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles custom post type registration.
 */
class PostTypes extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'init',
            [ $this, 'register_post_types' ]
        );
    }

    /**
     * Register WooShop custom post types.
     *
     * @return void
     */
    public function register_post_types(): void {

        /**
         * Custom post types will be registered here.
         *
         * WooCommerce-specific post types and feature-specific
         * post types should remain inside their respective modules.
         */
    }
}