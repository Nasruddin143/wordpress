<?php
/**
 * Homepage Latest Products
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

$products = wc_get_products(
    [
        'status' => 'publish',
        'limit'  => 8,
        'orderby' => 'date',
        'order'  => 'DESC',
        'return' => 'objects',
    ]
);

if ( empty( $products ) ) {
    return;
}
?>

<section
    class="ws-home-section ws-home-products ws-home-latest"
    aria-labelledby="ws-latest-products-title">

    <div class="ws-container">

        <header class="ws-section-header">

            <h2
                id="ws-latest-products-title"
                class="ws-section-title">

                <?php
                esc_html_e(
                    'Latest Products',
                    'wooshop'
                );
                ?>

            </h2>

            <a
                class="ws-section-header__link"
                href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">

                <?php
                esc_html_e(
                    'View All',
                    'wooshop'
                );
                ?>

            </a>

        </header>

        <div class="ws-product-grid">

            <?php foreach ( $products as $product ) : ?>

                <?php
                if ( ! $product instanceof WC_Product ) {
                    continue;
                }

                $post_object = get_post( $product->get_id() );

                if ( ! $post_object ) {
                    continue;
                }

                /*
                 * Set the global post so WooCommerce's
                 * content-product.php uses the correct product.
                 */
                $GLOBALS['post'] = $post_object;

                setup_postdata( $post_object );

                wc_get_template_part(
                    'content',
                    'product'
                );

                wp_reset_postdata();
                ?>

            <?php endforeach; ?>

        </div>

    </div>

</section>