<?php
/**
 * WooShop Search Module
 *
 * Provides centralized WordPress search configuration and
 * search-result body classes for the WooShop theme.
 *
 * WooCommerce product search behavior remains handled by
 * WooCommerce-specific modules.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Search
{
    /**
     * Register the search module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'body_class',
            [$this, 'filter_body_class']
        );

        add_filter(
            'pre_get_posts',
            [$this, 'filter_search_query']
        );

        add_filter(
            'get_search_form',
            [$this, 'filter_search_form']
        );
    }

    /**
     * Add WooShop search body classes.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function filter_body_class(array $classes): array
    {
        if (!is_search()) {
            return $classes;
        }

        $classes[] = 'ws-search-page';

        if ($this->is_product_search()) {
            $classes[] = 'ws-product-search';
        } else {
            $classes[] = 'ws-content-search';
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Configure the WordPress search query.
     *
     * Product search is intentionally excluded so WooCommerce
     * remains responsible for product-search behavior.
     *
     * @param \WP_Query $query Current query.
     *
     * @return void
     */
    public function filter_search_query(
        \WP_Query $query
    ): void {
        if (
            is_admin()
            || !$query->is_main_query()
            || !$query->is_search()
        ) {
            return;
        }

        /*
         * Keep the standard WordPress search query lightweight.
         *
         * WooCommerce product searches are handled separately.
         */
    }

    /**
     * Add WooShop classes to the generated search form.
     *
     * @param string $form Generated search form.
     *
     * @return string
     */
    public function filter_search_form(string $form): string
    {
        if ($form === '') {
            return $form;
        }

        if (str_contains($form, 'ws-search-form')) {
            return $form;
        }

        return str_replace(
            'search-form',
            'search-form ws-search-form',
            $form
        );
    }

    /**
     * Determine whether the current search is a product search.
     *
     * @return bool
     */
    private function is_product_search(): bool
    {
        if (!function_exists('is_woocommerce')) {
            return false;
        }

        return is_woocommerce() || is_post_type_archive('product');
    }
}