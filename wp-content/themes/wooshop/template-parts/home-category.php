<?php
/**
 * Homepage template part for Categories
 */

?>


<div id="wcProductCategories" class="product-categories py-5">

    <div class="section-title mb-5">
        <h2 class="text-center fw-bold">Explore Popular Categories</h2>
    </div>

    <div class="container text-center">
        <?php
        //echo do_shortcode('[product_categories columns="6" exclude="803,797,791" ]'); 
        
        $subcategories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));

        if (!empty($subcategories) && !is_wp_error($subcategories)): ?>

            <div class="row align-items-center">

                <?php foreach ($subcategories as $category):

                    // Skip parent categories
                    if ($category->parent == 0) {
                        continue;
                    }

                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

                    $image = wp_get_attachment_image_url($thumbnail_id, 'woocommerce_thumbnail');

                    if (!$image) {
                        $image = wc_placeholder_img_src();
                    }

                    $link = get_term_link($category); ?>


                    <div class="col-lg-2 col-md-3 col-sm-6 col-6 mb-3">

                        <div class="p-2 product-card">

                            <div class="product-image">

                                <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_html($category->name); ?>">

                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="img-fluid"
                                        loading="lazy">
                                </a>

                            </div>

                            <h3 class="card-title my-2 fs-6">

                                <a href="<?php echo esc_url($link); ?>"
                                    class="link-title link-offset-2 link-underline link-underline-opacity-0" title="<?php echo esc_html($category->name); ?>">

                                    <?php echo esc_html($category->name); ?>

                                </a>

                            </h3>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>
    </div>

</div>