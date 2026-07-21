<?php

use KT\Helpers\Ratings;

defined('ABSPATH') || exit;

$query = $args['query'] ?? null;
$title = $args['title'] ?? '';
$uid = uniqid('kt_swiper_');
?>

<?php if ($query && $query->have_posts()): ?>

    <div class="kt-section-header">

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 fw-bold mb-0"><?php echo esc_html($title); ?></h2>
            <div>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-dark py-2 px-3">
                    View All
                </a>

                <!-- Bootstrap Styled Buttons -->
                <button class="btn btn-dark kt-nav py-2 px-2 kt-prev-<?php echo $uid; ?>">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <button class="btn btn-dark kt- py-2 px-2 kt-next-<?php echo $uid; ?>">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

        </div>

        <!-- Swiper -->
        <div class="swiper <?php echo esc_attr($uid); ?>">
            <div class="swiper-wrapper">

                <?php while ($query->have_posts()):
                    $query->the_post();
                    global $product; ?>

                    <div class="swiper-slide h-100 woocommerce product">

                        <div class="border px-3 py-2 rounded-3">

                            <div class="product-image">
                                <a href="<?php the_permalink(); ?>" class="d-block link-offset-2 link-underline link-underline-opacity-0 link-dark">
                                    <?php echo woocommerce_get_product_thumbnail('woocommerce_thumbnail', [
                                        'class' => 'img-fluid'
                                    ]); ?>

                                    <?php if ($product->is_on_sale()): ?>
                                        <span class="kt-badge-sale">Sale</span>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="kt-product-body py-2">

                                <h3 class="kt-product-title h6 lh-sm mb-1">
                                    <a href="<?php the_permalink(); ?>" class="d-block link-offset-2 link-underline link-underline-opacity-0 link-dark"><?php the_title(); ?></a>
                                </h3>

                                <div class="kt-rating d-flex mb-2">
                                    <?php Ratings::render($product); ?>
                                </div>

                                <div class="kt-price d-flex gap-2 mb-2">
                                    <?php echo $product->get_price_html(); ?>
                                </div>

                                <div class="kt-cart">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                            </div>
                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            new Swiper(".<?php echo esc_js($uid); ?>", {
                slidesPerView: 2,
                spaceBetween: 16,

                breakpoints: {
                    576: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    992: {
                        slidesPerView: 4
                    },
                    1200: {
                        slidesPerView: 5
                    },
                },
                navigation: {
                    nextEl: ".kt-next-<?php echo $uid; ?>",
                    prevEl: ".kt-prev-<?php echo $uid; ?>"
                },
            });
        });
    </script>

<?php endif;
