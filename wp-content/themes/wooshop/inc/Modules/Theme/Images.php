<?php
/**
 * Theme Images
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Images extends Module {

    /**
     * Register hooks.
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'register_sizes' ],
            40
        );
    }

    /**
     * Register custom image sizes.
     */
    public function register_sizes(): void {

        $config = $this->container->get( Config::class );

        if ( ! $config instanceof Config ) {
            return;
        }

        $images = $config->get( 'images' );

        if ( empty( $images['sizes'] ) ) {
            return;
        }

        foreach ( $images['sizes'] as $name => $size ) {

            if ( ! is_array( $size ) || count( $size ) < 2 ) {
                continue;
            }

            $width = absint( $size[0] );
            $height = absint( $size[1] );
            $crop = ! empty( $size[2] );

            if ( ! $width || ! $height ) {
                continue;
            }

            add_image_size(
                $name,
                $width,
                $height,
                $crop
            );
        }
    }
}