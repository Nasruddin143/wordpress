<?php
/**
 * WooShop Cart Cross-sells.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (empty($cross_sells)) {
    return;
}

$columns = apply_filters(
    'woocommerce_cross_sells_columns',
    4
);

$heading = apply_filters(
    'woocommerce_product_cross_sells_products_heading',
    __('You may be interested in&hellip;', 'woocommerce')
);
?>

    <section class="cross-sells products">

        <?php if ($heading) : ?>

            <h2 class="section-title">
                <?php echo esc_html($heading); ?>
            </h2>

        <?php endif; ?>

        <div
            class="row g-4 wooshop-cross-sells"
            data-columns="<?php echo esc_attr($columns); ?>"
        >

            <?php foreach ($cross_sells as $cross_sell) : ?>

                <?php
                $post_object = get_post($cross_sell->get_id());

                if (!$post_object) {
                    continue;
                }

                setup_postdata($GLOBALS['post'] =& $post_object);

                wc_get_template_part('content', 'product');
                ?>

            <?php endforeach; ?>

        </div>

    </section>

<?php wp_reset_postdata(); ?>