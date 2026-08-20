<?php
/**
 * WooShop Product Compare
 *
 * Provides a lightweight product comparison system using
 * a browser cookie and AJAX product data.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Compare class.
 */
class Compare extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Compare cookie name.
     *
     * @var string
     */
    protected string $cookie = 'wooshop_compare';

    /**
     * Maximum products allowed.
     *
     * @var int
     */
    protected int $limit = 4;

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
            30
        );

        add_action(
            'wp_footer',
            array( $this, 'render_bar' )
        );

        add_action(
            'wp_ajax_wooshop_compare_toggle',
            array( $this, 'toggle' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_compare_toggle',
            array( $this, 'toggle' )
        );

        add_action(
            'wp_ajax_wooshop_compare_products',
            array( $this, 'get_products' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_compare_products',
            array( $this, 'get_products' )
        );
    }

    /**
     * Enqueue Compare assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $this->assets->load_component(
            'compare'
        );

        wp_localize_script(
            'wooshop-compare',
            'WooShopCompare',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce(
                    'wooshop_compare'
                ),
                'action'  => 'wooshop_compare_toggle',
                'dataAction' => 'wooshop_compare_products',
                'limit'   => $this->limit,
                'compareUrl' => $this->get_compare_url(),
                'addText' => __(
                    'Compare',
                    'wooshop'
                ),
                'removeText' => __(
                    'Remove from compare',
                    'wooshop'
                ),
                'limitText' => __(
                    'You can compare up to 4 products.',
                    'wooshop'
                ),
            )
        );
    }

    /**
     * Render compare button.
     *
     * @return void
     */
    public function render_button(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $product_id = $product->get_id();

        $active = in_array(
            $product_id,
            $this->get_compare_ids(),
            true
        );

        ?>
        <button
            type="button"
            class="btn ws-compare-button <?php echo $active ? 'is-active' : ''; ?>"
            data-ws-compare
            data-product-id="<?php echo esc_attr( $product_id ); ?>"
            aria-pressed="<?php echo $active ? 'true' : 'false'; ?>"
        >
            <?php
            echo esc_html(
                $active
                    ? __( 'Remove from compare', 'wooshop' )
                    : __( 'Compare', 'wooshop' )
            );
            ?>
        </button>
        <?php
    }

    /**
     * Render floating comparison bar.
     *
     * @return void
     */
    public function render_bar(): void {

        ?>
        <div
            class="ws-compare-bar"
            data-ws-compare-bar
            hidden
        >

            <div class="container">

                <div class="ws-compare-bar__inner">

                    <div
                        class="ws-compare-bar__items"
                        data-ws-compare-items
                    ></div>

                    <div class="ws-compare-bar__actions">

                        <a
                            href="<?php echo esc_url(
                                $this->get_compare_url()
                            ); ?>"
                            class="btn btn-primary"
                            data-ws-compare-link
                        >
                            <?php
                            esc_html_e(
                                'Compare',
                                'wooshop'
                            );
                            ?>
                        </a>

                        <button
                            type="button"
                            class="btn btn-link"
                            data-ws-compare-clear
                        >
                            <?php
                            esc_html_e(
                                'Clear',
                                'wooshop'
                            );
                            ?>
                        </button>

                    </div>

                </div>

            </div>

        </div>
        <?php
    }

    /**
     * Toggle a product in comparison.
     *
     * @return void
     */
    public function toggle(): void {

        check_ajax_referer(
            'wooshop_compare',
            'nonce'
        );

        $product_id = isset( $_POST['product_id'] )
            ? absint( $_POST['product_id'] )
            : 0;

        if (
            ! $product_id ||
            ! wc_get_product( $product_id )
        ) {
            wp_send_json_error(
                array(
                    'message' => __(
                        'Invalid product.',
                        'wooshop'
                    ),
                ),
                400
            );
        }

        $ids = $this->get_compare_ids();

        if ( in_array( $product_id, $ids, true ) ) {

            $ids = array_values(
                array_diff(
                    $ids,
                    array( $product_id )
                )
            );

            $active = false;

        } else {

            if ( count( $ids ) >= $this->limit ) {

                wp_send_json_error(
                    array(
                        'message' => __(
                            'You can compare up to 4 products.',
                            'wooshop'
                        ),
                        'limit'   => true,
                    )
                );
            }

            $ids[] = $product_id;

            $active = true;
        }

        $this->save_compare_ids( $ids );

        wp_send_json_success(
            array(
                'active' => $active,
                'ids'    => $ids,
                'count'  => count( $ids ),
            )
        );
    }

    /**
     * Return comparison product data.
     *
     * @return void
     */
    public function get_products(): void {

        check_ajax_referer(
            'wooshop_compare',
            'nonce'
        );

        $ids = $this->get_compare_ids();

        $products = array();

        foreach ( $ids as $product_id ) {

            $product = wc_get_product(
                $product_id
            );

            if ( ! $product ) {
                continue;
            }

            $products[] = $this->get_product_data(
                $product
            );
        }

        wp_send_json_success(
            array(
                'products' => $products,
            )
        );
    }

    /**
     * Build comparison data for a product.
     *
     * @param \WC_Product $product Product.
     * @return array
     */
    protected function get_product_data(
        \WC_Product $product
    ): array {

        return array(
            'id'          => $product->get_id(),
            'name'        => $product->get_name(),
            'url'         => get_permalink(
                $product->get_id()
            ),
            'image'       => wp_get_attachment_image_url(
                $product->get_image_id(),
                'woocommerce_thumbnail'
            ),
            'price'       => wp_strip_all_tags(
                $product->get_price_html()
            ),
            'rating'      => $product->get_average_rating(),
            'reviews'     => $product->get_review_count(),
            'sku'         => $product->get_sku(),
            'availability' => $product->is_in_stock()
                ? __( 'In stock', 'wooshop' )
                : __( 'Out of stock', 'wooshop' ),
        );
    }

    /**
     * Get comparison IDs from cookie.
     *
     * @return array
     */
    protected function get_compare_ids(): array {

        if ( empty( $_COOKIE[ $this->cookie ] ) ) {
            return array();
        }

        $ids = json_decode(
            wp_unslash(
                $_COOKIE[ $this->cookie ]
            ),
            true
        );

        if ( ! is_array( $ids ) ) {
            return array();
        }

        return array_values(
            array_unique(
                array_filter(
                    array_map(
                        'absint',
                        $ids
                    )
                )
            )
        );
    }

    /**
     * Save comparison IDs.
     *
     * @param array $ids Product IDs.
     * @return void
     */
    protected function save_compare_ids(
        array $ids
    ): void {

        $ids = array_slice(
            array_values(
                array_unique(
                    array_filter(
                        array_map(
                            'absint',
                            $ids
                        )
                    )
                )
            ),
            0,
            $this->limit
        );

        $value = wp_json_encode( $ids );

        setcookie(
            $this->cookie,
            $value,
            time() + YEAR_IN_SECONDS,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );

        $_COOKIE[ $this->cookie ] = $value;
    }

    /**
     * Get comparison page URL.
     *
     * @return string
     */
    protected function get_compare_url(): string {

        $page = get_page_by_path(
            'compare'
        );

        if ( $page instanceof \WP_Post ) {
            return get_permalink( $page );
        }

        return home_url( '/compare/' );
    }
}