<?php
/**
 * WooCommerce Module
 *
 * Main WooCommerce integration module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Class WooCommerce
 */
class WooCommerce extends Module {

    /**
     * Register WooCommerce functionality.
     *
     * @return void
     */
    protected function register(): void
    {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        /**
         * WooCommerce is available.
         *
         * Individual WooCommerce modules are registered
         * by the central ModuleManager/configuration layer.
         */
        do_action( 'wooshop_woocommerce_loaded' );
    }
}