<?php
/**
 * WooCommerce Mini Cart Module.
 *
 * Provides the WooShop Bootstrap offcanvas mini cart.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Mini cart module.
 */
final class MiniCart extends Module
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

        /*
         * Header trigger.
         */
        add_action('wooshop_header_actions', array($this, 'render_trigger'), 20);

        /*
         * Offcanvas markup.
         */
        add_action('wp_footer', array($this, 'render_offcanvas'), 20);

        /*
         * Refresh WooCommerce cart fragments.
         */
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_fragments'));
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
     * Render cart trigger.
     *
     * @return void
     */
    public function render_trigger(): void
    {

        if (!function_exists('WC') || !WC()->cart) {
            return;
        }

        $count = WC()->cart->get_cart_contents_count();
        ?>

        <button
                type="button"
                class="btn btn-link position-relative text-decoration-none p-2"
                data-bs-toggle="offcanvas"
                data-bs-target="#wooshop-mini-cart"
                aria-controls="wooshop-mini-cart"
                aria-label="<?php esc_attr_e('Open shopping cart', 'wooshop'); ?>">
			<span class="mini-cart-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                     class="main-grid-item-icon"
                     fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <circle cx="9" cy="21" r="1"/>
                  <circle cx="20" cy="21" r="1"/>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
			</span>

            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-primary mini-cart-count">
				<?php echo esc_html($count); ?>
			</span>

            <span class="visually-hidden">
				<?php
                _n(
                        '%d item in cart',
                        '%d items in cart',
                        $count,
                        'wooshop'
                )
                    |> esc_html(...)
                    |> (fn($x) => printf(                /* translators: %d: Number of items in cart. */ $x, $count));
                ?>
			</span>
        </button>

        <?php
    }

    /**
     * Render Bootstrap offcanvas mini cart.
     *
     * @return void
     */
    public function render_offcanvas(): void
    {

        if (!$this->is_available()) {
            return;
        }
        ?>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="wooshop-mini-cart"
             aria-labelledby="wooshop-mini-cart-label">

            <div class="offcanvas-header border-bottom">

                <h2 id="wooshop-mini-cart-label" class="offcanvas-title h5 mb-0">
                    <?php esc_html_e('Your Cart', 'wooshop'); ?>
                </h2>

                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        aria-label="<?php esc_attr_e('Close cart', 'wooshop'); ?>">
                </button>

            </div>

            <div class="offcanvas-body p-0">

                <div class="mini-cart-content p-3">
                    <?php $this->render_cart_content(); ?>
                </div>

            </div>

        </div>

        <?php
    }

    /**
     * Render cart content.
     *
     * @return void
     */
    private function render_cart_content(): void
    {

        if (function_exists('woocommerce_mini_cart')) {
            woocommerce_mini_cart();
            return;
        }

        esc_html_e('Your cart is currently empty.', 'wooshop');
    }

    /**
     * Refresh cart fragments.
     *
     * @param array<string, string> $fragments WooCommerce fragments.
     *
     * @return array<string, string>
     */
    public function cart_fragments(array $fragments): array
    {

        if (!function_exists('WC') || !WC()->cart) {
            return $fragments;
        }

        ob_start();

        $count = WC()->cart->get_cart_contents_count();
        ?>

        <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-primary mini-cart-count"
        >
			<?php echo esc_html($count); ?>
		</span>

        <?php

        $fragments['.mini-cart-count'] = ob_get_clean();

        ob_start();
        ?>

        <div class="mini-cart-content p-3">
            <?php $this->render_cart_content(); ?>
        </div>

        <?php

        $fragments['.mini-cart-content'] = ob_get_clean();

        return $fragments;
    }
}