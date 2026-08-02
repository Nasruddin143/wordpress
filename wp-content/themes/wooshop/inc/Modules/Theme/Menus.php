<?php
/**
 * Theme Menus
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Menus extends Module {

    /**
     * Register hooks.
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'register_menus' ],
            30
        );
    }

    /**
     * Register navigation menus.
     */
    public function register_menus(): void {

        $config = $this->container->get( Config::class );

        if ( ! $config instanceof Config ) {
            return;
        }

        $menus = $config->get( 'menus' );

        if ( empty( $menus ) ) {
            return;
        }

        register_nav_menus( $menus );
    }

    /**
     * Check menu location.
     */
    public static function has( string $location ): bool {

        return has_nav_menu( $location );
    }

    /**
     * Render menu.
     */
    public static function render(
        string $location,
        array $args = []
    ): void {

        if ( ! has_nav_menu( $location ) ) {
            return;
        }

        $defaults = [

            'theme_location' => $location,

            'container' => 'nav',

            'container_class' => 'menu-' . $location,

            'menu_class' => 'menu',

            'fallback_cb' => false,

            'depth' => 3,

        ];

        wp_nav_menu(
            wp_parse_args(
                $args,
                $defaults
            )
        );
    }
}