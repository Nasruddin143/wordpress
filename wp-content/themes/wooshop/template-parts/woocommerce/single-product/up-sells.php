<?php
/**
 * WooShop Product Upsells.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (empty($upsells)) {
    return;
}

$columns = apply_filters('woocommerce_upsells_columns', 4);

$heading = apply_filters('woocommerce_product_upsells_products_heading', __('You may also like&hellip;', 'woocommerce')); ?>

    <section class="up-sells upsells products">

        <?php if ($heading) : ?>

            <h2 class="section-title">
                <?php echo esc_html($heading); ?>
            </h2>

        <?php endif; ?>

        <div class="row g-4 wooshop-product-grid">

            <?php foreach ($upsells as $upsell) : ?>

                <?php $post_object = get_post($upsell->get_id());

                if (!$post_object) {
                    continue;
                }

                $GLOBALS['post'] = $post_object;

                setup_postdata($post_object);

                $GLOBALS['product'] = $upsell;

                do_action('woocommerce_shop_loop');
                ?>

                <div <?php wc_product_class('col-12 col-sm-6 col-lg-4', $upsell); ?> >

                    <?php get_template_part('template-parts/woocommerce/product/card', null, ['product' => $upsell,]); ?>
                </div>

            <?php endforeach; ?>

        </div>

        <?php wp_reset_postdata(); ?>

    </section>

<?php wp_reset_postdata();