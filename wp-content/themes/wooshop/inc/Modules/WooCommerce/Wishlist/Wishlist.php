<?php
/**
 * WooCommerce Wishlist Module
 *
 * Provides the product-card wishlist foundation.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce\Wishlist;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Wishlist extends Module
{
    /**
     * Register module.
     */
    public function register(): void
    {
        add_action(
            'ws_product_card_media_actions',
            [ $this, 'render_button' ],
            10
        );

        add_filter(
            'body_class',
            [ $this, 'body_classes' ]
        );

        add_action(
            'wp_enqueue_scripts',
            [ $this, 'enqueue_assets' ]
        );
    }

    /**
     * Add wishlist body class.
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes( array $classes ): array
    {
        $classes[] = 'ws-wishlist-enabled';

        return $classes;
    }

    /**
     * Enqueue wishlist assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        if ( ! function_exists( 'is_woocommerce' ) ) {
            return;
        }

        if (
            ! is_woocommerce()
            && ! is_cart()
            && ! is_checkout()
            && ! is_account_page()
        ) {
            return;
        }

        wp_enqueue_style(
            'wooshop-wishlist',
            get_template_directory_uri() . '/assets/dist/css/components/wishlist.css',
            [],
            defined( 'WOOSHOP_VERSION' ) ? WOOSHOP_VERSION : null
        );

        wp_enqueue_script(
            'wooshop-wishlist',
            get_template_directory_uri() . '/assets/dist/js/components/wishlist.js',
            [],
            defined( 'WOOSHOP_VERSION' ) ? WOOSHOP_VERSION : null,
            true
        );
    }

    /**
     * Render wishlist button.
     *
     * @return void
     */
    public function render_button(): void
    {
        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $product_id = $product->get_id();

        ?>

        <button
                type="button"
                class="ws-wishlist-button"
                data-product-id="<?php echo esc_attr( $product_id ); ?>"
                data-product-name="<?php echo esc_attr( $product->get_name() ); ?>"
                aria-label="<?php echo esc_attr(
                        sprintf(
                                __( 'Add %s to wishlist', 'wooshop' ),
                                $product->get_name()
                        )
                ); ?>"
                aria-pressed="false"
        >

			<span
                class="ws-wishlist-button__icon"
                aria-hidden="true"
            >
				<span class="ws-wishlist-button__heart">
					♡
				</span>
			</span>

            <span class="screen-reader-text">
				<?php
                printf(
                /* translators: %s: product name. */
                    esc_html__(
                        'Add %s to wishlist',
                        'wooshop'
                    ),
                    esc_html( $product->get_name() )
                );
                ?>
			</span>

        </button>

        <?php
    }
}