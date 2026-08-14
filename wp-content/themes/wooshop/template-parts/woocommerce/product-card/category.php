<?php
/**
 * Product Card Category.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}

$categories = wp_get_post_terms(
    $product->get_id(),
    'product_cat',
    [
        'number' => 1,
    ]
);

if ( empty( $categories ) || is_wp_error( $categories ) ) {
    return;
}
?>

<div class="ws-product-card__category">

    <a
        href="<?php echo esc_url( get_term_link( $categories[0] ) ); ?>"
    >
        <?php
        echo esc_html(
            $categories[0]->name
        );
        ?>
    </a>

</div>