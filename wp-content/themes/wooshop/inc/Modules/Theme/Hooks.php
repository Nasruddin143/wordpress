<?php
/**
 * Theme Hooks Module.
 *
 * Registers global WordPress hooks used by the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles global WooShop theme hooks.
 */
class Hooks extends Module {

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
            'wp_head',
            [ $this, 'head' ],
            1
        );

        add_action(
            'wp_body_open',
            [ $this, 'body_open' ],
            1
        );

        add_action(
            'wp_footer',
            [ $this, 'footer' ],
            1
        );
    }

    /**
     * Handle the beginning of the document head.
     *
     * @return void
     */
    public function head(): void {

        /**
         * Reserved for theme-level head functionality.
         */
    }

    /**
     * Handle the beginning of the document body.
     *
     * @return void
     */
    public function body_open(): void {

        /**
         * Reserved for theme-level body-open functionality.
         */
    }

    /**
     * Handle the end of the document body.
     *
     * @return void
     */
    public function footer(): void {

        /**
         * Reserved for theme-level footer functionality.
         */
    }
}