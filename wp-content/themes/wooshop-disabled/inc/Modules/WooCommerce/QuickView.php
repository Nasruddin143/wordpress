<?php
/**
 * WooShop Quick View
 *
 * Provides an AJAX-powered quick-view modal for WooCommerce products.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * QuickView class.
 */
class QuickView extends Module {

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
            'woocommerce_after_shop_loop_item',
            array( $this, 'render_button' ),
            25
        );

        add_action(
            'wp_ajax_wooshop_quick_view',
            array( $this, 'render_quick_view' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_quick_view',
            array( $this, 'render_quick_view' )
        );
    }

    /**
     * Enqueue Quick View assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if (
            ! is_shop()
            && ! is_product_category()
            && ! is_product_tag()
            && ! is_product_taxonomy()
        ) {
            return;
        }

        $this->assets->load_component(
            'quick-view'
        );

        wp_localize_script(
            'wooshop-quick-view',
            'WooShopQuickView',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce(
                    'wooshop_quick_view'
                ),
                'action'  => 'wooshop_quick_view',
                'loading' => __( 'Loading product…', 'wooshop' ),
                'error'   => __( 'Unable to load product.', 'wooshop' ),
            )
        );
    }

    /**
     * Render Quick View button.
     *
     * @return void
     */
    public function render_button(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        ?>
        <button
            type="button"
            class="btn ws-quick-view-button"
            data-ws-quick-view
            data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
            aria-label="<?php esc_attr_e( 'Quick view product', 'wooshop' ); ?>"
        >
            <?php esc_html_e( 'Quick View', 'wooshop' ); ?>
        </button>
        <?php
    }

    /**
     * Render Quick View product content.
     *
     * @return void
     */
    public function render_quick_view(): void {

        check_ajax_referer(
            'wooshop_quick_view',
            'nonce'
        );

        $product_id = isset( $_POST['product_id'] )
            ? absint( $_POST['product_id'] )
            : 0;

        if ( ! $product_id ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Invalid product.', 'wooshop' ),
                ),
                400
            );
        }

        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Product not found.', 'wooshop' ),
                ),
                404
            );
        }

        ob_start();

        ?>

        <div
            class="ws-quick-view"
            data-ws-quick-view-content
        >

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="ws-quick-view__image">

                        <?php
                        echo wp_kses_post(
                            $product->get_image(
                                'woocommerce_single'
                            )
                        );
                        ?>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ws-quick-view__details">

                        <h2 class="ws-quick-view__title">
                            <?php
                            echo esc_html(
                                $product->get_name()
                            );
                            ?>
                        </h2>

                        <div class="ws-quick-view__rating">
                            <?php
                            echo wp_kses_post(
                                wc_get_rating_html(
                                    $product->get_average_rating(),
                                    $product->get_rating_count()
                                )
                            );
                            ?>
                        </div>

                        <div class="ws-quick-view__price">
                            <?php
                            echo wp_kses_post(
                                $product->get_price_html()
                            );
                            ?>
                        </div>

                        <div class="ws-quick-view__summary">
                            <?php
                            echo wp_kses_post(
                                wpautop(
                                    $product->get_short_description()
                                )
                            );
                            ?>
                        </div>

                        <?php
                        $this->render_purchase_form(
                            $product
                        );
                        ?>

                        <a
                            class="btn btn-link px-0"
                            href="<?php echo esc_url(
                                get_permalink( $product_id )
                            ); ?>"
                        >
                            <?php
                            esc_html_e(
                                'View full product',
                                'wooshop'
                            );
                            ?>
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php

        $html = ob_get_clean();

        wp_send_json_success(
            array(
                'html' => $html,
            )
        );
    }

    /**
     * Render the product purchase form.
     *
     * @param \WC_Product $product Product object.
     * @return void
     */
    protected function render_purchase_form(
        \WC_Product $product
    ): void {

        ?>
        <form
            class="cart ws-quick-view__cart"
            method="post"
            action="<?php echo esc_url(
                $product->get_permalink()
            ); ?>"
        >

            <?php
            if ( $product->is_type( 'variable' ) ) {

                /*
                 * Variable products continue to use the
                 * native WooCommerce variation system.
                 */
                wc_get_template(
                    'single-product/add-to-cart/variable.php',
                    array(
                        'available_variations' =>
                            $product->get_available_variations(),
                        'attributes' =>
                            $product->get_variation_attributes(),
                        'selected_attributes' => array(),
                    )
                );

            } elseif ( $product->is_purchasable() ) {

                woocommerce_quantity_input(
                    array(
                        'min_value' => $product->get_min_purchase_quantity(),
                        'max_value' => $product->get_max_purchase_quantity(),
                    )
                );

                ?>
                <button
                    type="submit"
                    class="single_add_to_cart_button button btn btn-primary"
                    name="add-to-cart"
                    value="<?php echo esc_attr(
                        $product->get_id()
                    ); ?>"
                >
                    <?php
                    echo esc_html(
                        $product->single_add_to_cart_text()
                    );
                    ?>
                </button>
                <?php
            }
            ?>

        </form>
        <?php
    }
}