<?php
/**
 * WooShop Taxonomies Module
 *
 * Provides centralized registration support for theme-specific
 * taxonomies without modifying WooCommerce-owned taxonomies.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Taxonomies
{
    /**
     * Register the taxonomies' module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'init',
            [$this, 'register_taxonomies']
        );
    }

    /**
     * Register WooShop theme taxonomies.
     *
     * WooCommerce product categories, product tags, product
     * attributes, and other WooCommerce taxonomies remain under
     * WooCommerce-specific modules.
     *
     * @return void
     */
    public function register_taxonomies(): void
    {
        /*
         * No custom theme taxonomies are registered by the
         * base WooShop theme at this stage.
         *
         * Feature-specific taxonomies should be registered
         * by their corresponding feature module.
         */
    }
}