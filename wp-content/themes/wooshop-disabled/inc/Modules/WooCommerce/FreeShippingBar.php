<?php
/**
 * WooShop Free Shipping Bar
 *
 * Displays the amount remaining before the customer
 * qualifies for free shipping.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * FreeShippingBar class.
 */
class FreeShippingBar extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Constructor.
     *
     * @param \WooShop\Core\Container $container Service container.
     * @param AssetsManager             $assets    Asset manager.
     */
    public function __construct(
        \WooShop\Core\Container $container,
        AssetsManager $assets
    ) {
        parent::__construct( $container );

        $this->assets = $assets;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_assets' ),
            30
        );

        add_action(
            'wp_body_open',
            array( $this, 'render_bar' ),
            20
        );

        add_filter(
            'woocommerce_add_to_cart_fragments',
            array( $this, 'refresh_fragment' )
        );
    }

    /**
     * Enqueue Free Shipping Bar assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if ( ! $this->has_free_shipping_method() ) {
            return;
        }

        $this->assets->load_component(
            'free-shipping-bar'
        );

        wp_localize_script(
            'wooshop-free-shipping-bar',
            'WooShopFreeShipping',
            array(
                'ajaxUrl' => admin_url(
                    'admin-ajax.php'
                ),
                'nonce'   => wp_create_nonce(
                    'wooshop_free_shipping'
                ),
            )
        );
    }

    /**
     * Determine whether free shipping is configured.
     *
     * @return bool
     */
    protected function has_free_shipping_method(): bool {

        $zones = \WC_Shipping_Zones::get_zones();

        foreach ( $zones as $zone ) {

            $methods = $zone['shipping_methods'];

            foreach ( $methods as $method ) {

                if (
                    'free_shipping' ===
                    $method->id
                    &&
                    'enabled' ===
                    $method->enabled
                ) {
                    return true;
                }
            }
        }

        $zone = \WC_Shipping_Zones::get_zone(
            0
        );

        foreach (
            $zone->get_shipping_methods()
            as $method
        ) {

            if (
                'free_shipping' ===
                $method->id
                &&
                'enabled' ===
                $method->enabled
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get free-shipping threshold.
     *
     * Uses the first configured free-shipping
     * minimum order amount.
     *
     * @return float
     */
    protected function get_threshold(): float {

        $zones = \WC_Shipping_Zones::get_zones();

        foreach ( $zones as $zone ) {

            foreach (
                $zone['shipping_methods']
                as $method
            ) {

                if (
                    'free_shipping' !==
                    $method->id
                ) {
                    continue;
                }

                if (
                    'enabled' !==
                    $method->enabled
                ) {
                    continue;
                }

                $requires =
                    $method->get_option(
                        'requires'
                    );

                if (
                    'min_amount' !==
                    $requires
                ) {
                    continue;
                }

                $amount =
                    (float) $method->get_option(
                        'min_amount',
                        0
                    );

                if ( $amount > 0 ) {
                    return $amount;
                }
            }
        }

        $zone = \WC_Shipping_Zones::get_zone(
            0
        );

        foreach (
            $zone->get_shipping_methods()
            as $method
        ) {

            if (
                'free_shipping' !==
                $method->id
            ) {
                continue;
            }

            $requires =
                $method->get_option(
                    'requires'
                );

            if (
                'min_amount' !==
                $requires
            ) {
                continue;
            }

            $amount =
                (float) $method->get_option(
                    'min_amount',
                    0
                );

            if ( $amount > 0 ) {
                return $amount;
            }
        }

        return 0;
    }

    /**
     * Get current cart amount used for
     * free-shipping calculation.
     *
     * @return float
     */
    protected function get_cart_amount(): float {

        if (
            ! WC()->cart
            || WC()->cart->is_empty()
        ) {
            return 0;
        }

        return (float) WC()->cart->get_displayed_subtotal();
    }

    /**
     * Get current bar data.
     *
     * @return array
     */
    protected function get_bar_data(): array {

        $threshold = $this->get_threshold();
        $cart      = $this->get_cart_amount();

        if ( $threshold <= 0 ) {
            return array(
                'available' => false,
            );
        }

        $remaining = max(
            0,
            $threshold - $cart
        );

        $progress = min(
            100,
            ( $cart / $threshold ) * 100
        );

        $qualified = $remaining <= 0;

        if ( $qualified ) {

            $message = __(
                'Congratulations! You qualify for free shipping.',
                'wooshop'
            );

        } else {

            $message = sprintf(
            /* translators: %s: amount remaining. */
                __(
                    'Add %s more to get free shipping.',
                    'wooshop'
                ),
                wc_price( $remaining )
            );
        }

        return array(
            'available'  => true,
            'threshold'  => $threshold,
            'cart'       => $cart,
            'remaining'  => $remaining,
            'progress'   => $progress,
            'qualified'  => $qualified,
            'message'    => $message,
        );
    }

    /**
     * Render Free Shipping Bar.
     *
     * @return void
     */
    public function render_bar(): void {

        $data = $this->get_bar_data();

        if ( empty( $data['available'] ) ) {
            return;
        }

        ?>
        <div
            class="ws-free-shipping-bar"
            data-ws-free-shipping
            data-threshold="<?php echo esc_attr(
                $data['threshold']
            ); ?>"
            data-cart="<?php echo esc_attr(
                $data['cart']
            ); ?>"
        >

            <div class="container">

                <div class="ws-free-shipping-bar__inner">

                    <div
                        class="ws-free-shipping-bar__message"
                        data-ws-free-shipping-message
                    >
                        <?php
                        echo wp_kses_post(
                            $data['message']
                        );
                        ?>
                    </div>

                    <div
                        class="ws-free-shipping-bar__progress"
                        role="progressbar"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        aria-valuenow="<?php echo esc_attr(
                            $data['progress']
                        ); ?>"
                    >
						<span
                            data-ws-free-shipping-progress
                            style="width: <?php echo esc_attr(
                                $data['progress']
                            ); ?>%;"
                        ></span>
                    </div>

                </div>

            </div>

        </div>
        <?php
    }

    /**
     * Refresh the shipping-bar fragment.
     *
     * @param array $fragments Existing fragments.
     * @return array
     */
    public function refresh_fragment(
        array $fragments
    ): array {

        ob_start();

        $this->render_bar();

        $html = ob_get_clean();

        $fragments[
        '.ws-free-shipping-bar'
        ] = $html;

        return $fragments;
    }
}