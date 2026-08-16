<?php
/**
 * Theme Metadata Module.
 *
 * Handles theme-level WordPress metadata functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop metadata functionality.
 */
class Metadata extends Module {

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
            [ $this, 'output_metadata' ],
            1
        );
    }

    /**
     * Output theme-level metadata.
     *
     * Keep SEO metadata out of this module when an SEO plugin
     * is responsible for it.
     *
     * @return void
     */
    public function output_metadata(): void {

        /**
         * Reserved for genuinely theme-owned metadata.
         *
         * SEO title, description, Open Graph and schema markup
         * should be handled by the site's SEO system.
         */
    }
}