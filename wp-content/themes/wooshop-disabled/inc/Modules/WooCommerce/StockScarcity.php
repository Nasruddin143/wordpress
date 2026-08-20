<?php
/**
 * WooShop Stock Scarcity
 *
 * Displays a stock-scarcity message for products
 * with genuinely low inventory.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * StockScarcity class.
 */
class StockScarcity extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Stock threshold.
     *
     * Products with stock at or below this number
     * will display the scarcity message.
     *
     * @var int
     */
    protected int $threshold = 5;

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
            array( $this, 'render_single_product' ),
            25
        );

        add_action(
            'woocommerce_after_shop_loop_item_title',
            array( $this, 'render_loop_product' ),
            15
        );
    }

    /**
     * Enqueue Stock Scarcity assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if (
            ! is_product()
            && ! is_shop()
            && ! is_product_category()
            && ! is_product_tag()
        ) {
            return;
        }

        $this->assets->load_component(
            'stock-scarcity'
        );
    }

    /**
     * Render scarcity message on single product.
     *
     * @return void
     */
    public function render_single_product(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $this->render_message( $product );
    }

    /**
     * Render scarcity message in product loops.
     *
     * @return void
     */
    public function render_loop_product(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $this->render_message(
            $product,
            true
        );
    }

    /**
     * Render the scarcity message.
     *
     * @param \WC_Product $product Product.
     * @param bool         $compact Compact loop presentation.
     * @return void
     */
    protected function render_message(
        \WC_Product $product,
        bool $compact = false
    ): void {

        if ( ! $product->managing_stock() ) {
            return;
        }

        if ( ! $product->is_in_stock() ) {
            return;
        }

        $stock_quantity = $product->get_stock_quantity();

        if ( null === $stock_quantity ) {
            return;
        }

        if ( $stock_quantity <= 0 ) {
            return;
        }

        if ( $stock_quantity > $this->threshold ) {
            return;
        }

        $message = sprintf(
        /* translators: %d: remaining product quantity. */
            _n(
                'Only %d left in stock',
                'Only %d left in stock',
                $stock_quantity,
                'wooshop'
            ),
            $stock_quantity
        );

        ?>
        <div
            class="ws-stock-scarcity <?php echo $compact ? 'ws-stock-scarcity--compact' : ''; ?>"
            role="status"
            data-stock-quantity="<?php echo esc_attr( $stock_quantity ); ?>"
        >

			<span
                class="ws-stock-scarcity__icon"
                aria-hidden="true"
            >
				!
			</span>

            <span class="ws-stock-scarcity__message">
				<?php echo esc_html( $message ); ?>
			</span>

        </div>
        <?php
    }
}