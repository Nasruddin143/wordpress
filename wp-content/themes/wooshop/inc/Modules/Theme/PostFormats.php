<?php
/**
 * Theme Post Formats Module.
 *
 * Registers supported WordPress post formats for WooShop.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress post formats.
 */
class PostFormats extends Module {

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
            'after_setup_theme',
            [ $this, 'register_post_formats' ]
        );
    }

    /**
     * Register supported WordPress post formats.
     *
     * @return void
     */
    public function register_post_formats(): void {

        add_theme_support(
            'post-formats',
            [
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            ]
        );
    }
}