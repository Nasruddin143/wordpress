<?php
/**
 * WooShop WooCommerce Shop Module
 *
 * Handles shop archive functionality and template hooks.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Shop
 */
final class Shop extends Module
{
    /**
     * Cached theme configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        $this->config = $this->container->get('config')->get('theme');

        /*
         * Remove WooCommerce default archive UI.
         */
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

        remove_action('woocommerce_no_products_found', 'wc_no_products_found', 10);

        /*
         * WooShop toolbar.
         */
        add_action('woocommerce_before_shop_loop', [$this, 'render_shop_toolbar'], 20);

        /*
         * WooShop empty state.
         */
        add_action('woocommerce_no_products_found', [$this, 'render_empty_state'], 10);
    }

    /**
     * Render shop toolbar.
     *
     * @return void
     */
    public function render_shop_toolbar(): void
    {
        if (!$this->is_shop_archive()) {
            return;
        }

        get_template_part('template-parts/woocommerce/shop/toolbar', null, ['config' => $this->config,]);
    }

    /**
     * Render empty state.
     *
     * @return void
     */
    public function render_empty_state(): void
    {
        get_template_part('template-parts/woocommerce/shop/empty', null, ['config' => $this->config,]);
    }

    /**
     * Determine whether current page is a product archive.
     *
     * @return bool
     */
    private function is_shop_archive(): bool
    {
        return function_exists('is_shop') && (is_shop() || is_product_taxonomy());
    }
}