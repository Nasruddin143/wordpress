<?php
/**
 * WordPress Navigation.
 *
 * Registers navigation menu locations for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress navigation menus.
 */
class Navigation extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'register_menus' ]
        );
    }

    /**
     * Register theme navigation menu locations.
     *
     * @return void
     */
    public function register_menus(): void {

        register_nav_menus(
            [
                'primary' => esc_html__( 'Primary Menu', 'wooshop' ),
                'secondary' => esc_html__( 'Secondary Menu', 'wooshop' ),
                'footer' => esc_html__( 'Footer Menu', 'wooshop' ),
                'mobile' => esc_html__( 'Mobile Menu', 'wooshop' ),
            ]
        );
    }
}