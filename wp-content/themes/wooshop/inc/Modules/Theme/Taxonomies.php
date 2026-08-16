<?php
/**
 * WordPress Taxonomies.
 *
 * Provides centralized registration for WooShop custom taxonomies.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles custom taxonomy registration.
 */
class Taxonomies extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'init',
            [ $this, 'register_taxonomies' ]
        );
    }

    /**
     * Register WooShop custom taxonomies.
     *
     * @return void
     */
    public function register_taxonomies(): void {

        /**
         * Custom taxonomies will be registered here.
         *
         * WooCommerce taxonomies and feature-specific taxonomies
         * must remain inside their respective modules.
         */
    }
}