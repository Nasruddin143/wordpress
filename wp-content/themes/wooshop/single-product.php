<?php
/**
 * Single Product Template.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

get_header();

?>

    <main id="primary" class="site-main">

        <div class="woocommerce-single-product py-5">

            <div class="container">

                <?php get_template_part('template-parts/woocommerce/shop/breadcrumb'); ?>

                <div class="py-4 py-lg-5">

                    <?php while (have_posts()) : ?>

                        <?php
                        the_post();

                        wc_get_template_part('content', 'single-product');
                        ?>

                    <?php endwhile; ?>

                </div>
            </div>

        </div>

    </main>

<?php do_action('woocommerce_after_main_content');

get_footer();