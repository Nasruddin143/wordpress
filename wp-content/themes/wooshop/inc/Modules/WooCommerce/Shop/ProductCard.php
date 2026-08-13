<?php
/**
 * WooCommerce Product Card Module
 *
 * Handles WooShop product-card integration.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce\Shop;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class ProductCard extends Module
{
    /**
     * Register module.
     */
    public function register(): void
    {
        add_action(
            'ws_product_card_badges',
            [ $this, 'render_badges' ],
            10
        );

        add_action(
            'ws_product_card_after_title',
            [ $this, 'render_review_count' ],
            10
        );
    }

    /**
     * Render product badges.
     *
     * @return void
     */
    public function render_badges(): void
    {
        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $this->render_sale_badge( $product );
        $this->render_stock_badge( $product );
    }

    /**
     * Render sale badge.
     *
     * @param \WC_Product $product Product object.
     * @return void
     */
    protected function render_sale_badge( \WC_Product $product ): void
    {
        if ( ! $product->is_on_sale() ) {
            return;
        }

        ?>

        <span class="ws-product-badge ws-product-badge--sale">
			<?php esc_html_e( 'Sale', 'wooshop' ); ?>
		</span>

        <?php
    }

    /**
     * Render stock badge.
     *
     * @param \WC_Product $product Product object.
     * @return void
     */
    protected function render_stock_badge( \WC_Product $product ): void
    {
        if ( $product->is_in_stock() ) {
            return;
        }

        ?>

        <span class="ws-product-badge ws-product-badge--out-of-stock">
			<?php esc_html_e( 'Out of stock', 'wooshop' ); ?>
		</span>

        <?php
    }

    /**
     * Render review count.
     *
     * @return void
     */
    public function render_review_count(): void
    {
        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $review_count = $product->get_review_count();

        if ( $review_count < 1 ) {
            return;
        }

        ?>

        <span class="ws-product-review-count">
			<?php
            printf(
            /* translators: %s: number of reviews. */
                esc_html(
                    _n(
                        '%s review',
                        '%s reviews',
                        $review_count,
                        'wooshop'
                    )
                ),
                esc_html(
                    number_format_i18n( $review_count )
                )
            );
            ?>
		</span>

        <?php
    }
}