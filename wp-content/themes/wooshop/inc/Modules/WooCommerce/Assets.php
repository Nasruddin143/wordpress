<?php
/**
 * WooCommerce Assets Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * WooCommerce assets module.
 */
final class Assets extends Module
{

    /**
     * Theme directory path.
     *
     * @var string
     */
    private string $theme_path;

    /**
     * Theme directory URI.
     *
     * @var string
     */
    private string $theme_uri;

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);

        $this->theme_path = get_template_directory();
        $this->theme_uri = get_template_directory_uri();
    }

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

        add_action('wp_enqueue_scripts', array($this, 'register_assets'), 30);

        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 40);
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

    /**
     * Register WooCommerce assets.
     *
     * @return void
     */
    public function register_assets(): void
    {

        $css_file = $this->theme_path . '/assets/build/css/woocommerce.min.css';

        $js_file = $this->theme_path . '/assets/build/js/woocommerce.min.js';

        if (file_exists($css_file)) {
            wp_register_style('wooshop-woocommerce', $this->theme_uri . '/assets/build/css/woocommerce.min.css', array('app'), $this->get_version());
        }

        if (file_exists($js_file)) {
            wp_register_script('wooshop-woocommerce', $this->theme_uri . '/assets/build/js/woocommerce.min.js', array('app'), $this->get_version(), true);
        }
    }

    /**
     * Enqueue WooCommerce assets when required.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {

        if (!$this->is_woocommerce_context()) {
            return;
        }

        if (wp_style_is('woocommerce', 'registered')) {
            wp_enqueue_style('woocommerce');
        }

        if (wp_script_is('woocommerce', 'registered')) {
            wp_enqueue_script('woocommerce');
        }
    }

    /**
     * Determine whether the current request uses WooCommerce.
     *
     * @return bool
     */
    private function is_woocommerce_context(): bool
    {
        // Core WooCommerce templates (Shop, Single Product, Archive)
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return true;
        }

        // Main Shop page
        if (function_exists('is_shop') && is_shop()) {
            return true;
        }

        // Single Product pages
        if (function_exists('is_product') && is_product()) {
            return true;
        }

        // Cart page
        if (function_exists('is_cart') && is_cart()) {
            return true;
        }

        // Checkout and Order Received/Pay pages
        if (function_exists('is_checkout') && is_checkout()) {
            return true;
        }

        // My Account pages
        if (function_exists('is_account_page') && is_account_page()) {
            return true;
        }

        // Product Category archives
        if (function_exists('is_product_category') && is_product_category()) {
            return true;
        }

        // Product Tag archives
        if (function_exists('is_product_tag') && is_product_tag()) {
            return true;
        }

        // Product Taxonomy archives (e.g., custom attributes, brands)
        if (function_exists('is_product_taxonomy') && is_product_taxonomy()) {
            return true;
        }

        // WooCommerce REST API or Store API requests
//        if (defined('WC_REQUEST') && WC_REQUEST) {
//            return true;
//        }

        // WooCommerce Ajax requests
        if (wp_doing_ajax() && isset($_REQUEST['wc-ajax'])) {
            return true;
        }

        return false;
    }


    /**
     * Get theme version.
     *
     * @return string
     */
    private function get_version(): string
    {
        return (string)wp_get_theme()->get('Version');
    }
}