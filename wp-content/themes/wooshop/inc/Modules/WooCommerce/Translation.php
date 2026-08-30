<?php
/**
 * WooCommerce Translation Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * WooCommerce translation module.
 */
final class Translation extends Module
{
    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if (!$this->is_available()) {
            return;
        }

        /*
         * WooCommerce already handles its own translation loading.
         *
         * WooShop-specific WooCommerce strings should use the
         * `wooshop` text domain directly in the relevant modules.
         */
    }

    /**
     * Check WooCommerce availability.
     *
     * @return bool
     */
    private function is_available(): bool
    {
        return class_exists('WooCommerce');
    }
}