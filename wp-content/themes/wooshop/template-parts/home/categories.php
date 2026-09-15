<?php
/**
 * Homepage Product Categories.
 *
 * Displays WooCommerce product categories in a lightweight carousel.
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

<section
        class="home-categories py-5"
        aria-labelledby="home-categories-title"
>

    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">

            <div>
                <h2 id="home-categories-title" class="h3 mb-1 fw-semibold">
                    <?php esc_html_e('Explore Popular Categories', 'wooshop'); ?>
                </h2>

                <p class="text-body-secondary mb-0">
                    <?php esc_html_e('Explore our product categories.', 'wooshop'); ?>
                </p>
            </div>

            <div class="home-categories__controls d-flex gap-2">

                <button type="button" class="btn btn-primary btn-wooshop home-categories__prev"
                        aria-label="<?php esc_attr_e('Previous categories', 'wooshop'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
                         class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>

                </button>

                <button type="button" class="btn btn-primary btn-wooshop home-categories__next"
                        aria-label="<?php esc_attr_e('Next categories', 'wooshop'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
                         class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>

                </button>

            </div>

        </div>

        <div class="home-categories__carousel">

            <div class="home-categories__track" tabindex="0"
                 aria-label="<?php esc_attr_e('Product categories', 'wooshop'); ?>">

                <?php foreach ($categories as $category) : ?>

                    <?php
                    $category_link = get_term_link($category);

                    if (is_wp_error($category_link)) {
                        continue;
                    }

                    $thumbnail_id = (int)get_term_meta(
                            $category->term_id,
                            'thumbnail_id',
                            true
                    );
                    ?>

                    <div class="home-categories__slide">

                        <a class="home-category-card d-block text-decoration-none"
                           href="<?php echo esc_url($category_link); ?>">

                            <div class="home-category-card__inner p-3 bg-light">

                                <?php if ($thumbnail_id) : ?>

                                    <?php
                                    echo wp_get_attachment_image(
                                            $thumbnail_id,
                                            'woocommerce_thumbnail',
                                            false,
                                            [
                                                    'class' => 'home-category-card__image rounded mx-auto d-block mb-2',
                                                    'loading' => 'lazy',
                                                    'alt' => $category->name,
                                            ]
                                    );
                                    ?>

                                <?php else : ?>

                                    <div
                                            class="home-category-card__placeholder bg-body-secondary d-flex align-items-center justify-content-center"
                                            aria-hidden="true"
                                    >
                                        <span class="text-body-secondary">
                                            <?php esc_html_e('No image', 'wooshop'); ?>
                                        </span>
                                    </div>

                                <?php endif; ?>

                                <div class="text-center">

                                    <h3 class="h6 fw-bold mb-2">
                                        <?php echo esc_html($category->name); ?>
                                    </h3>

                                    <p class="small text-body-secondary mb-0">

                                        <?php
                                        printf(
                                        /* translators: %d: number of products. */
                                                esc_html(
                                                        _n(
                                                                '%d Product',
                                                                '%d Products',
                                                                (int)$category->count,
                                                                'wooshop'
                                                        )
                                                ),
                                                (int)$category->count
                                        );
                                        ?>

                                    </p>

                                </div>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</section>