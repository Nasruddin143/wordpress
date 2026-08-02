<?php
/**
 * Theme Sidebars
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Sidebars extends Module {

    /**
     * Register module hooks.
     */
    public function register(): void {

        add_action(
            'widgets_init',
            [ $this, 'register_sidebars' ]
        );
    }

    /**
     * Register all configured sidebars.
     */
    public function register_sidebars(): void {

        $config = $this->container->get( Config::class );

        if ( ! $config instanceof Config ) {
            return;
        }

        $sidebars = $config->get( 'sidebars' );

        if ( empty( $sidebars ) || ! is_array( $sidebars ) ) {
            return;
        }

        foreach ( $sidebars as $sidebar ) {

            $this->register_sidebar( $sidebar );
        }
    }

    /**
     * Register one sidebar.
     *
     * @param array $sidebar Sidebar configuration.
     */
    protected function register_sidebar( array $sidebar ): void {

        if ( empty( $sidebar['id'] ) || empty( $sidebar['name'] ) ) {
            return;
        }

        register_sidebar(
            [
                'id'            => sanitize_key( $sidebar['id'] ),
                'name'          => $sidebar['name'],
                'description'   => $sidebar['description'] ?? '',
                'before_widget' => $sidebar['before_widget'] ?? '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => $sidebar['after_widget'] ?? '</section>',
                'before_title'  => $sidebar['before_title'] ?? '<h2 class="widget-title">',
                'after_title'   => $sidebar['after_title'] ?? '</h2>',
            ]
        );
    }

    /**
     * Check whether a sidebar contains widgets.
     *
     * @param string $id Sidebar ID.
     *
     * @return bool
     */
    public static function is_active( string $id ): bool {

        return is_active_sidebar( $id );
    }
}