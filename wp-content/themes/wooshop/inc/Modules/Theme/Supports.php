<?php
/**
 * Theme Supports
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Supports extends Module {

    /**
     * Register hooks.
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'load' ],
            20
        );
    }

    /**
     * Register theme supports.
     */
    public function load(): void {

        $config = $this->container->get( Config::class );

        if ( ! $config instanceof Config ) {
            return;
        }

        $supports = $config->get( 'supports' );

        foreach ( $supports as $support ) {

            if ( is_array( $support ) ) {

                add_theme_support(
                    $support[0],
                    $support[1]
                );

                continue;
            }

            add_theme_support( $support );
        }
    }
}