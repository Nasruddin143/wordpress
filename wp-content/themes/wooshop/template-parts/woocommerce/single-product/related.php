<?php
/**
 * WooShop Related Products.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (empty($related_products)) {
    return;
}
?>

    <section class="related products py-5">

        <?php $heading = apply_filters('woocommerce_product_related_products_heading', __('You may be interested', 'woocommerce'));

        if ($heading) : ?>
            <h2 class="products-section__title py-4">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <div class="row g-3 wooshop-product-grid">

            <?php foreach ($related_products as $related_product) : ?>

                <?php
                $post_object = get_post($related_product->get_id());

                if (!$post_object) {
                    continue;
                }

                $GLOBALS['post'] = $post_object;

                setup_postdata($post_object);

                $GLOBALS['product'] = $related_product;

                do_action('woocommerce_shop_loop'); ?>


                <?php get_template_part('template-parts/woocommerce/product/card', null, ['product' => $related_product,]); ?>


            <?php endforeach; ?>

        </div>

    </section>

<?php wp_reset_postdata();