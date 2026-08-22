<?php
/**
 * Jetpack Compatibility Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Jetpack compatibility module.
 */
final class Jetpack extends Module {

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {

        if ( ! defined( 'JETPACK__VERSION' ) ) {
            return;
        }

        add_action(
            'init',
            array( $this, 'register_support' )
        );
    }

    /**
     * Register Jetpack theme compatibility.
     *
     * @return void
     */
    public function register_support(): void {

        /*
         * Keep this module limited to actual Jetpack compatibility.
         * Jetpack-specific implementation can be added here when needed.
         */
    }
}