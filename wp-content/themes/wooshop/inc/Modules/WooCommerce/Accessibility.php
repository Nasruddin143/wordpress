<?php
/**
 * WooCommerce Accessibility Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * WooCommerce accessibility module.
 */
final class Accessibility extends Module
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
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if (!$this->is_available()) {
            return;
        }

        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'add_product_aria_label'), 10, 3);
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
     * Add an accessible label to loop add-to-cart links.
     *
     * @param string $html Existing markup.
     * @param mixed $product Product object.
     * @param mixed $args Button arguments.
     *
     * @return string
     */
    public function add_product_aria_label(string $html, mixed $product, mixed $args): string
    {

        unset($args);

        if (!is_object($product) || !method_exists($product, 'get_name')) {
            return $html;
        }

        if (str_contains($html, 'aria-label=')) {
            return $html;
        }

        $label = sprintf( /* translators: %s: Product name. */ __('Add %s to your cart', 'wooshop'), $product->get_name());

        return str_replace('<a ', '<a aria-label="' . esc_attr($label) . '" ', $html);
    }
}