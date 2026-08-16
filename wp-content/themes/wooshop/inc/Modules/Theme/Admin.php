<?php
/**
 * Theme Admin Module.
 *
 * Handles WooShop-specific WordPress administration functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop administration functionality.
 */
class Admin extends Module {

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
            'admin_enqueue_scripts',
            [ $this, 'enqueue_admin_assets' ]
        );

        add_filter(
            'admin_body_class',
            [ $this, 'admin_body_classes' ]
        );
    }

    /**
     * Enqueue theme-specific admin assets.
     *
     * Admin assets should remain separate from frontend assets.
     *
     * @return void
     */
    public function enqueue_admin_assets(): void {

        /**
         * Admin-specific assets will be registered here
         * when the WooShop admin UI requires them.
         */
    }

    /**
     * Add WooShop classes to the admin body.
     *
     * @param string $classes Existing admin body classes.
     * @return string
     */
    public function admin_body_classes( string $classes ): string {

        $classes .= ' wooshop-admin';

        return trim( $classes );
    }
}