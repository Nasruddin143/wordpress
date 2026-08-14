<?php
/**
 * WooCommerce Product Module
 *
 * Handles WooShop single-product integration.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce\Product;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Product extends Module
{
    /**
     * Register module.
     */
    public function register(): void
    {
        add_filter(
            'body_class',
            [ $this, 'body_classes' ]
        );
    }

    /**
     * Add WooShop product body classes.
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes( array $classes ): array
    {
        if ( ! function_exists( 'is_product' ) || ! is_product() ) {
            return $classes;
        }

        $classes[] = 'ws-single-product';

        return $classes;
    }
}