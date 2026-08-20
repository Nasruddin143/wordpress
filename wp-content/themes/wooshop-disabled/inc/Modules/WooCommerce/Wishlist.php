<?php
/**
 * WooShop Product Wishlist
 *
 * Provides a lightweight wishlist using the WordPress session/cookie
 * for guests and user meta for logged-in customers.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Wishlist class.
 */
class Wishlist extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Wishlist cookie name.
     *
     * @var string
     */
    protected string $cookie = 'wooshop_wishlist';

    /**
     * User meta key.
     *
     * @var string
     */
    protected string $meta_key = '_wooshop_wishlist';

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
            'wp_ajax_wooshop_wishlist_toggle',
            array( $this, 'toggle' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_wishlist_toggle',
            array( $this, 'toggle' )
        );

        add_action(
            'woocommerce_after_shop_loop_item',
            array( $this, 'render_button' ),
            20
        );

        add_action(
            'woocommerce_after_add_to_cart_button',
            array( $this, 'render_single_button' ),
            20
        );
    }

    /**
     * Enqueue wishlist assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if (
            ! is_shop()
            && ! is_product()
            && ! is_product_category()
            && ! is_product_tag()
            && ! is_product_taxonomy()
        ) {
            return;
        }

        $this->assets->load_component( 'wishlist' );

        wp_localize_script(
            'wooshop-wishlist',
            'WooShopWishlist',
            array(
                'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'wooshop_wishlist' ),
                'action'   => 'wooshop_wishlist_toggle',
                'addText'  => __( 'Add to wishlist', 'wooshop' ),
                'removeText' => __( 'Remove from wishlist', 'wooshop' ),
            )
        );
    }

    /**
     * Render wishlist button on product loops.
     *
     * @return void
     */
    public function render_button(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $this->render_button_markup(
            $product->get_id()
        );
    }

    /**
     * Render wishlist button on single products.
     *
     * @return void
     */
    public function render_single_button(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $this->render_button_markup(
            $product->get_id(),
            'ws-wishlist-button--single'
        );
    }

    /**
     * Render wishlist button markup.
     *
     * @param int    $product_id Product ID.
     * @param string $class      Additional CSS class.
     * @return void
     */
    protected function render_button_markup(
        int $product_id,
        string $class = ''
    ): void {

        $active = $this->has_product( $product_id );

        ?>
        <button
            type="button"
            class="btn ws-wishlist-button <?php echo esc_attr( $class ); ?>"
            data-ws-wishlist
            data-product-id="<?php echo esc_attr( $product_id ); ?>"
            aria-pressed="<?php echo $active ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr(
                $active
                    ? __( 'Remove from wishlist', 'wooshop' )
                    : __( 'Add to wishlist', 'wooshop' )
            ); ?>"
        >
			<span
                class="ws-wishlist-button__icon"
                aria-hidden="true"
            >
				♡
			</span>

            <span class="ws-wishlist-button__text">
				<?php
                echo esc_html(
                    $active
                        ? __( 'Remove from wishlist', 'wooshop' )
                        : __( 'Add to wishlist', 'wooshop' )
                );
                ?>
			</span>
        </button>
        <?php
    }

    /**
     * Toggle a product in the wishlist.
     *
     * @return void
     */
    public function toggle(): void {

        check_ajax_referer(
            'wooshop_wishlist',
            'nonce'
        );

        $product_id = isset( $_POST['product_id'] )
            ? absint( $_POST['product_id'] )
            : 0;

        if ( ! $product_id || ! wc_get_product( $product_id ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Invalid product.', 'wooshop' ),
                ),
                400
            );
        }

        $wishlist = $this->get_wishlist();

        if ( in_array( $product_id, $wishlist, true ) ) {

            $wishlist = array_values(
                array_diff(
                    $wishlist,
                    array( $product_id )
                )
            );

            $active = false;

        } else {

            $wishlist[] = $product_id;

            $wishlist = array_values(
                array_unique(
                    array_map(
                        'absint',
                        $wishlist
                    )
                )
            );

            $active = true;
        }

        $this->save_wishlist( $wishlist );

        wp_send_json_success(
            array(
                'active' => $active,
                'count'  => count( $wishlist ),
                'message' => $active
                    ? __( 'Product added to wishlist.', 'wooshop' )
                    : __( 'Product removed from wishlist.', 'wooshop' ),
            )
        );
    }

    /**
     * Get the current wishlist.
     *
     * @return array
     */
    protected function get_wishlist(): array {

        if ( is_user_logged_in() ) {

            $wishlist = get_user_meta(
                get_current_user_id(),
                $this->meta_key,
                true
            );

            return is_array( $wishlist )
                ? array_map( 'absint', $wishlist )
                : array();
        }

        if ( empty( $_COOKIE[ $this->cookie ] ) ) {
            return array();
        }

        $wishlist = json_decode(
            wp_unslash(
                $_COOKIE[ $this->cookie ]
            ),
            true
        );

        return is_array( $wishlist )
            ? array_map( 'absint', $wishlist )
            : array();
    }

    /**
     * Save the wishlist.
     *
     * @param array $wishlist Product IDs.
     * @return void
     */
    protected function save_wishlist( array $wishlist ): void {

        $wishlist = array_values(
            array_unique(
                array_filter(
                    array_map( 'absint', $wishlist )
                )
            )
        );

        if ( is_user_logged_in() ) {

            update_user_meta(
                get_current_user_id(),
                $this->meta_key,
                $wishlist
            );

            return;
        }

        setcookie(
            $this->cookie,
            wp_json_encode( $wishlist ),
            time() + MONTH_IN_SECONDS * 12,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );

        $_COOKIE[ $this->cookie ] = wp_json_encode( $wishlist );
    }

    /**
     * Determine whether a product is in the wishlist.
     *
     * @param int $product_id Product ID.
     * @return bool
     */
    protected function has_product( int $product_id ): bool {

        return in_array(
            $product_id,
            $this->get_wishlist(),
            true
        );
    }
}