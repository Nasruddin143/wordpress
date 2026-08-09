<?php
/**
 * Product Card Information.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="ws-product-card__info">

    <div class="ws-product-card__rating">

        <?php
        echo wc_get_rating_html(
            $product->get_average_rating(),
            $product->get_rating_count()
        );
        ?>

    </div>

    <div class="ws-product-card__price">

        <?php
        echo wp_kses_post(
            $product->get_price_html()
        );
        ?>

    </div>

</div>