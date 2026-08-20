<?php
/**
 * WooCommerce Product Card
 *
 * Displays a single product inside the shop archive.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
    return;
}
?>

<article
    id="product-<?php the_ID(); ?>"
    <?php wc_product_class( 'ws-product-card h-100', $product ); ?>
>

    <div class="card h-100 border-0">

        <div class="ws-product-image position-relative">

            <a
                href="<?php echo esc_url( $product->get_permalink() ); ?>"
                class="d-block text-decoration-none"
            >

                <?php
                echo wp_kses_post(
                    $product->get_image(
                        'woocommerce_thumbnail',
                        array(
                            'class' => 'img-fluid w-100',
                        )
                    )
                );
                ?>

            </a>

            <?php if ( $product->is_on_sale() ) : ?>

                <span class="ws-product-badge position-absolute top-0 start-0 badge bg-danger m-2">

					<?php esc_html_e( 'Sale', 'wooshop' ); ?>

				</span>

            <?php endif; ?>

            <?php if ( ! $product->is_in_stock() ) : ?>

                <span class="ws-product-badge position-absolute top-0 end-0 badge bg-secondary m-2">

					<?php esc_html_e( 'Out of stock', 'wooshop' ); ?>

				</span>

            <?php endif; ?>

        </div>

        <div class="card-body px-0">

            <h2 class="ws-product-title h6 mb-2">

                <a
                    href="<?php echo esc_url( $product->get_permalink() ); ?>"
                    class="text-decoration-none text-body"
                >
                    <?php echo esc_html( $product->get_name() ); ?>
                </a>

            </h2>

            <?php if ( $product->get_average_rating() ) : ?>

                <div class="ws-product-rating small mb-2">

                    <?php
                    echo wp_kses_post(
                        wc_get_rating_html(
                            $product->get_average_rating(),
                            $product->get_rating_count()
                        )
                    );
                    ?>

                </div>

            <?php endif; ?>

            <div class="ws-product-price">

                <?php
                echo wp_kses_post(
                    $product->get_price_html()
                );
                ?>

            </div>

        </div>

        <div class="card-footer bg-transparent border-0 px-0">

            <?php
            echo sprintf(
                '<a href="%s" class="btn btn-primary w-100">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_html( $product->add_to_cart_text() )
            );
            ?>

        </div>

    </div>

</article>