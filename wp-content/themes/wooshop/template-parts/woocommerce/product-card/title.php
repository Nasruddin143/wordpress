<?php
/**
 * Product Card Title.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<h2 class="ws-product-card__title">

    <a
        href="<?php echo esc_url( $product->get_permalink() ); ?>"
    >
        <?php
        echo esc_html(
            $product->get_name()
        );
        ?>
    </a>

</h2>