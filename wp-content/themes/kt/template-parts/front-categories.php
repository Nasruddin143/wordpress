<?php

use KT\Features\WooCommerceQuery;

$terms = WooCommerceQuery::product_categories(12, 'product_cat');

if (!empty($terms)):
?>

    <h2 class="h3 fw-bold mb-4">Featured Categories</h2>

    <div class="row g-3">
        <?php foreach ($terms as $term):
            // Get category image ID
            $thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        ?>

            <div class="col-sm-6 col-md-3 col-lg-2">

                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="link-offset-2 link-underline link-underline-opacity-0 link-dark">
                    <div class="border px-3 py-4 rounded-3 text-center">
                        <!-- CATEGORY IMAGE -->
                        <?php
                        if ($thumb_id) {
                            echo wp_get_attachment_image(
                                $thumb_id,
                                'woocommerce_thumbnail',
                                false,
                                [
                                    'class' => 'img-fluid',
                                    'alt' => esc_attr($term->name),
                                    'loading' => 'lazy'
                                ]
                            );
                        } else {
                            // fallback image (optional)
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/default-cat.svg" class="kt-cat-img img-fluid" alt="Category">';
                        }
                        ?>

                        <!-- CATEGORY NAME -->
                        <h3 class="h6 mb-0"><?php echo esc_html($term->name); ?></h3>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;
