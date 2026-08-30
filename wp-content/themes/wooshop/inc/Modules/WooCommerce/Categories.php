<?php
/**
 * WooCommerce Subcategories Module.
 *
 * Provides product Category data for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WP_Term;

defined('ABSPATH') || exit;

/**
 * WooCommerce Categories Module.
 */
final class Categories extends Module
{

    /**
     * Register module functionality.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('init', [$this, 'register_hooks']);
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register_hooks(): void
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        add_filter('wooshop_home_categories', [$this, 'get_categories']);
    }

    /**
     * Get all product categories for the homepage.
     *
     * Parent categories are excluded.
     *
     * @param array<int, WP_Term> $categories Existing categories.
     * @return array<int, WP_Term>
     */
    public function get_categories(array $categories = []): array
    {
        $categories = get_terms(
            [
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]
        );

        if (is_wp_error($categories)) {
            return [];
        }

        return array_values(array_filter($categories, static fn(WP_Term $category): bool => (int)$category->parent > 0));
    }
}