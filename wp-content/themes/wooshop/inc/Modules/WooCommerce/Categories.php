<?php
/**
 * WooCommerce Categories Module.
 *
 * Provides product category data for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;
use WP_Term;

defined('ABSPATH') || exit;

/**
 * WooCommerce Categories Module.
 */
final class Categories extends Module
{

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);
    }

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
     * Register hooks.
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
     * Get product subcategories for the homepage.
     *
     * @param array<int, WP_Term> $categories Existing categories.
     * @return array<int, WP_Term>
     */
    public function get_categories(array $categories = []): array
    {
        $categories = get_terms(
            [
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
//                'number' => $this->limit,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]
        );

        if (is_wp_error($categories)) {
            return [];
        }

        $categories = array_filter(
            $categories,
            static function (WP_Term $category): bool {
                return 0 !== (int)$category->parent;
            }
        );

        return array_values($categories);
    }
}