<?php
/**
 * WooShop Sale Countdown
 *
 * Displays a lightweight countdown timer for products
 * with an active WooCommerce scheduled sale.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * SaleCountdown class.
 */
class SaleCountdown extends Module {

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
            'woocommerce_single_product_summary',
            array( $this, 'render_countdown' ),
            11
        );
    }

    /**
     * Enqueue countdown assets only when required.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        global $product;

        if ( ! $this->has_active_sale( $product ) ) {
            return;
        }

        $this->assets->load_component(
            'sale-countdown'
        );
    }

    /**
     * Determine whether a product has an active scheduled sale.
     *
     * @param \WC_Product|false $product Product object.
     * @return bool
     */
    protected function has_active_sale(
        $product
    ): bool {

        if ( ! $product instanceof \WC_Product ) {
            return false;
        }

        if ( ! $product->is_on_sale() ) {
            return false;
        }

        $sale_end = $product->get_date_on_sale_to();

        if ( ! $sale_end ) {
            return false;
        }

        return $sale_end->getTimestamp() > time();
    }

    /**
     * Render the sale countdown.
     *
     * @return void
     */
    public function render_countdown(): void {

        global $product;

        if ( ! $this->has_active_sale( $product ) ) {
            return;
        }

        $sale_end = $product->get_date_on_sale_to();

        if ( ! $sale_end ) {
            return;
        }

        $timestamp = $sale_end->getTimestamp();

        ?>

        <div
            class="ws-sale-countdown"
            data-ws-sale-countdown
            data-end-time="<?php echo esc_attr(
                $timestamp
            ); ?>"
            aria-label="<?php esc_attr_e(
                'Sale ends in',
                'wooshop'
            ); ?>"
        >

            <div class="ws-sale-countdown__content">

                <div class="ws-sale-countdown__heading">

					<span class="ws-sale-countdown__icon">
						<span aria-hidden="true">⏳</span>
					</span>

                    <span>
						<?php
                        esc_html_e(
                            'Sale ends in',
                            'wooshop'
                        );
                        ?>
					</span>

                </div>

                <div
                    class="ws-sale-countdown__timer"
                    data-ws-countdown-timer
                    aria-live="polite"
                >

                    <div class="ws-sale-countdown__unit">

                        <strong
                            data-ws-countdown-days
                        >
                            00
                        </strong>

                        <span>
							<?php
                            esc_html_e(
                                'Days',
                                'wooshop'
                            );
                            ?>
						</span>

                    </div>

                    <span
                        class="ws-sale-countdown__separator"
                        aria-hidden="true"
                    >
						:
					</span>

                    <div class="ws-sale-countdown__unit">

                        <strong
                            data-ws-countdown-hours
                        >
                            00
                        </strong>

                        <span>
							<?php
                            esc_html_e(
                                'Hours',
                                'wooshop'
                            );
                            ?>
						</span>

                    </div>

                    <span
                        class="ws-sale-countdown__separator"
                        aria-hidden="true"
                    >
						:
					</span>

                    <div class="ws-sale-countdown__unit">

                        <strong
                            data-ws-countdown-minutes
                        >
                            00
                        </strong>

                        <span>
							<?php
                            esc_html_e(
                                'Min',
                                'wooshop'
                            );
                            ?>
						</span>

                    </div>

                    <span
                        class="ws-sale-countdown__separator"
                        aria-hidden="true"
                    >
						:
					</span>

                    <div class="ws-sale-countdown__unit">

                        <strong
                            data-ws-countdown-seconds
                        >
                            00
                        </strong>

                        <span>
							<?php
                            esc_html_e(
                                'Sec',
                                'wooshop'
                            );
                            ?>
						</span>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}