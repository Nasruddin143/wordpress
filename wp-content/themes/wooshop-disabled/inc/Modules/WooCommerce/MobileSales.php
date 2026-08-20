<?php
/**
 * WooShop Mobile Sales
 *
 * Provides mobile-focused purchasing controls for WooCommerce.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * MobileSales class.
 */
class MobileSales extends Module {

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
            'wp_footer',
            array( $this, 'render_mobile_bar' ),
            20
        );
    }

    /**
     * Enqueue mobile sales assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if ( ! is_product() ) {
            return;
        }

        $this->assets->load_component(
            'mobile-sales'
        );
    }

    /**
     * Render the mobile sticky purchase bar.
     *
     * @return void
     */
    public function render_mobile_bar(): void {

        if ( ! is_product() ) {
            return;
        }

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        if (
            ! $product->is_purchasable()
            || ! $product->is_in_stock()
        ) {
            return;
        }

        ?>
        <div
            class="ws-mobile-sales"
            data-ws-mobile-sales
        >

            <div class="ws-mobile-sales__inner">

                <div class="ws-mobile-sales__product">

                    <?php
                    echo wp_kses_post(
                        $product->get_image(
                            'woocommerce_thumbnail'
                        )
                    );
                    ?>

                    <div class="ws-mobile-sales__info">

                        <strong class="ws-mobile-sales__name">
                            <?php
                            echo esc_html(
                                $product->get_name()
                            );
                            ?>
                        </strong>

                        <span class="ws-mobile-sales__price">
							<?php
                            echo wp_kses_post(
                                $product->get_price_html()
                            );
                            ?>
						</span>

                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-primary ws-mobile-sales__button"
                    data-ws-mobile-add
                >
                    <?php
                    echo esc_html(
                        $product->single_add_to_cart_text()
                    );
                    ?>
                </button>

            </div>

        </div>
        <?php
    }
}