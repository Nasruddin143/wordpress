<?php
/**
 * Theme Navigation
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Navigation extends Module {

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {

        /**
         * Register navigation hooks here.
         *
         * Example:
         *
         * add_filter(...);
         * add_action(...);
         */

    }

    /**
     * Render a menu.
     *
     * @param string $location Menu location.
     * @param array  $args     Additional arguments.
     *
     * @return void
     */
    public function render(
        string $location,
        array $args = []
    ): void {

        $defaults = [

            'theme_location' => $location,

            'container' => 'nav',

            'fallback_cb' => false,

        ];

        wp_nav_menu(
            wp_parse_args(
                $args,
                $defaults
            )
        );
    }
}