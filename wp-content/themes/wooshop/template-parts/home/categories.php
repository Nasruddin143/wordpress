<?php
/**
 * Homepage Product Categories.
 *
 * Displays WooCommerce product categories.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    return;
}

$categories = apply_filters('wooshop_home_categories', []);

if (empty($categories)) {
    return;
}
?>

<section class="home-categories py-5" aria-labelledby="home-categories-title">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">

            <div>
                <h2 id="home-categories-title" class="h3 mb-1">
                    <?php esc_html_e('Shop by Category', 'wooshop'); ?>
                </h2>

                <p class="text-body-secondary mb-0">
                    <?php esc_html_e('Explore our product categories.', 'wooshop'); ?>
                </p>
            </div>

        </div>

        <div class="row">

            <?php foreach ($categories as $category) : ?>

                <?php
                $category_link = get_term_link($category);

                if (is_wp_error($category_link)) {
                    continue;
                }

                $thumbnail_id = (int)get_term_meta($category->term_id, 'thumbnail_id', true);
                ?>

                <div class="col-6 col-md-3 col-lg-2">

                    <a
                            class="card rounded text-decoration-none overflow-hidden"
                            href="<?php echo esc_url($category_link); ?>"
                    >

                        <?php if ($thumbnail_id) : ?>

                            <?php
                            echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail',
                                    false,
                                    [
                                            'class' => 'card-img-top img-fluid',
                                            'loading' => 'lazy',
                                            'alt' => $category->name,
                                    ]
                            );
                            ?>

                        <?php else : ?>

                            <div
                                    class="card-img-top bg-body-secondary d-flex align-items-center justify-content-center"
                                    aria-hidden="true"
                            >
								<span class="text-body-secondary">
									<?php esc_html_e('No image', 'wooshop'); ?>
								</span>
                            </div>

                        <?php endif; ?>

                        <div class="card-body">

                            <h3 class="h6 card-title mb-1">
                                <?php echo esc_html($category->name); ?>
                            </h3>

                            <p class="card-text small text-body-secondary mb-0">
                                <?php
                                _n(
                                        '%d product',
                                        '%d products',
                                        (int)$category->count,
                                        'wooshop'
                                )
                                    |> esc_html(...)
                                    |> (fn($x) => printf( /* translators: %d: number of products. */ $x, (int)$category->count));
                                ?>
                            </p>

                        </div>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>