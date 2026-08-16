<?php
/**
 * Theme Admin Bar Module.
 *
 * Handles theme-specific WordPress admin bar functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop admin bar functionality.
 */
class AdminBar extends Module {

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        parent::__construct( $container );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'admin_bar_menu',
            [ $this, 'admin_bar_menu' ],
            100
        );
    }

    /**
     * Modify the WordPress admin bar.
     *
     * @param \WP_Admin_Bar $wp_admin_bar Admin bar instance.
     * @return void
     */
    public function admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ): void {

        /**
         * Theme-specific admin bar items can be added here.
         *
         * Do not modify the default WordPress admin bar unless
         * WooShop has a specific requirement.
         */
    }
}