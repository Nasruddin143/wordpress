<?php
/**
 * Header Actions Module.
 *
 * Provides cart, wishlist, and account actions
 * for the WooShop header.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Header actions module.
 */
final class Actions extends Module
{
    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        /*
         * Render header actions.
         */
        add_action('wooshop_header_actions', array($this, 'render_actions'), 20);

        /*
         * Render mini cart offcanvas.
         */
        add_action('wp_footer', array($this, 'render_mini_cart'), 20);

        /*
         * Refresh cart fragments.
         */
        if ($this->is_woocommerce_available()) {
            add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_fragments'));
        }
    }

    /**
     * Check WooCommerce availability.
     *
     * @return bool
     */
    private function is_woocommerce_available(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Render header actions.
     *
     * @return void
     */
    public function render_actions(): void
    {
        get_template_part('template-parts/header/actions');
    }

    /**
     * Render mini cart.
     *
     * @return void
     */
    public function render_mini_cart(): void
    {
        if (!$this->is_woocommerce_available()) {
            return;
        }

        get_template_part('template-parts/woocommerce/mini-cart');
    }

    /**
     * Get cart item count.
     *
     * @return int
     */
    public function get_cart_count(): int
    {
        if (!function_exists('WC') || !WC()->cart) {
            return 0;
        }

        return WC()->cart->get_cart_contents_count();
    }

    /**
     * Render mini cart content.
     *
     * @return void
     */
    public function render_cart_content(): void
    {
        if (function_exists('woocommerce_mini_cart')) {
            woocommerce_mini_cart();
            return;
        }

        esc_html_e('Your cart is currently empty.', 'wooshop');
    }

    /**
     * Refresh WooCommerce cart fragments.
     *
     * @param array<string, string> $fragments Cart fragments.
     *
     * @return array<string, string>
     */
    public function cart_fragments(array $fragments): array
    {
        if (!function_exists('WC') || !WC()->cart) {
            return $fragments;
        }

        /*
         * Cart count.
         */
        ob_start();

        $count = $this->get_cart_count();        ?>

        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-primary mini-cart-count">
            <?php echo esc_html($count); ?>
        </span>

        <?php

        $fragments['.mini-cart-count'] = ob_get_clean();


        /*
         * Mini cart content.
         */
        ob_start();

        get_template_part('template-parts/woocommerce/mini-cart-content');

        $fragments['.mini-cart-content'] = ob_get_clean();

        return $fragments;
    }
}