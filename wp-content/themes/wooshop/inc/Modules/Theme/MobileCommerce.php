<?php
/**
 * Mobile Commerce Module
 *
 * Provides mobile-first WooCommerce conversion enhancements.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * MobileCommerce class.
 */
class MobileCommerce {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register() {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_assets' ),
            30
        );

        add_action(
            'wp_footer',
            array( $this, 'render_mobile_product_bar' ),
            20
        );
    }

    /**
     * Enqueue mobile commerce assets.
     *
     * @return void
     */
    public function enqueue_assets() {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if ( ! is_product() ) {
            return;
        }

        wp_enqueue_style(
            'wooshop-mobile-commerce',
            get_stylesheet_directory_uri() .
            '/assets/build/css/components/mobile-commerce.min.css',
            array( 'wooshop-app' ),
            filemtime(
                get_stylesheet_directory() .
                '/assets/build/css/components/mobile-commerce.min.css'
            )
        );

        wp_enqueue_script(
            'wooshop-mobile-commerce',
            get_stylesheet_directory_uri() .
            '/assets/build/js/components/mobile-commerce.min.js',
            array(),
            filemtime(
                get_stylesheet_directory() .
                '/assets/build/js/components/mobile-commerce.min.js'
            ),
            true
        );
    }

    /**
     * Render the mobile sticky product action bar.
     *
     * @return void
     */
    public function render_mobile_product_bar() {

        if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
            return;
        }

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
            return;
        }

        $product_id = $product->get_id();

        ?>
        <div
            class="ws-mobile-product-bar"
            data-product-id="<?php echo esc_attr( $product_id ); ?>"
            aria-label="<?php esc_attr_e( 'Mobile product actions', 'wooshop' ); ?>"
        >

            <div class="ws-mobile-product-bar__inner">

                <div class="ws-mobile-product-bar__price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>

                <button
                    type="button"
                    class="btn btn-primary ws-mobile-product-bar__button"
                    data-ws-mobile-add-to-cart
                >
                    <?php esc_html_e( 'Add to cart', 'wooshop' ); ?>
                </button>

            </div>

        </div>
        <?php
    }
}