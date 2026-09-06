<?php
/**
 * WooShop Single Product Image.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

global $product;

if (!$product instanceof WC_Product) {
    return;
}
?>

<div class="product-single__image">

    <?php
    $image_id = $product->get_image_id();

    if ($image_id) :
        ?>

        <a
                href="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>"
                class="d-block"
        >
            <?php
            echo wp_get_attachment_image(
                    $image_id,
                    'woocommerce_single',
                    false,
                    array(
                            'class' => 'img-fluid',
                    )
            );
            ?>
        </a>

    <?php endif; ?>

</div>