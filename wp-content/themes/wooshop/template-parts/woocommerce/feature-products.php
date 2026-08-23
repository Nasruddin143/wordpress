<?php
/*
 * WooCommerce product section.
 *
 * Only display when WooCommerce is active.
 */
if (class_exists('WooCommerce')) : ?>

<section class="products-section py-5">

    <div class="container">

        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">

            <div>
                <p class="text-uppercase small fw-semibold text-body-secondary mb-2">
                    <?php esc_html_e('Shop', 'wooshop'); ?>
                </p>

                <h2 class="h2 mb-0">
                    <?php esc_html_e('Featured Products', 'wooshop'); ?>
                </h2>
            </div>

            <?php if (function_exists('wc_get_page_permalink')) : ?>

                <a
                        class="btn btn-outline-primary"
                        href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
                >
                    <?php esc_html_e('View Shop', 'wooshop'); ?>
                </a>

            <?php endif; ?>

        </div>

        <?php
        $products = wc_get_products(
            array(
                'status' => 'publish',
                'limit' => 8,
                'featured' => true,
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        if (!empty($products)) : ?>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">

                <?php foreach ($products as $product) : ?>

                    <div class="col">

                        <?php
                        $product_id = $product->get_id();

                        $image_id = $product->get_image_id();

                        $product_url = $product->get_permalink();

                        $product_title = $product->get_name();
                        ?>

                        <article
                            <?php
                            post_class(
                                'card h-100 border-0 shadow-sm',
                                $product_id
                            );
                            ?>
                        >

                            <a
                                    class="text-decoration-none"
                                    href="<?php echo esc_url($product_url); ?>"
                            >

                                <?php
                                if ($image_id) :

                                    echo wp_get_attachment_image(
                                        $image_id,
                                        'woocommerce_thumbnail',
                                        false,
                                        array(
                                            'class' => 'card-img-top img-fluid',
                                            'alt' => $product_title,
                                        )
                                    );

                                else :
                                    ?>

                                    <div class="ratio ratio-1x1 bg-body-secondary d-flex align-items-center justify-content-center">

												<span class="text-body-secondary small">
													<?php esc_html_e('No image', 'wooshop'); ?>
												</span>

                                    </div>

                                <?php endif; ?>

                            </a>

                            <div class="card-body d-flex flex-column p-3 p-md-4">

                                <h3 class="card-title h6 mb-2">

                                    <a
                                            class="text-decoration-none text-body"
                                            href="<?php echo esc_url($product_url); ?>"
                                    >
                                        <?php echo esc_html($product_title); ?>
                                    </a>

                                </h3>

                                <div class="mb-3">

                                    <?php
                                    echo wp_kses_post(
                                        $product->get_price_html()
                                    );
                                    ?>

                                </div>

                                <div class="mt-auto">

                                    <a
                                            class="btn btn-primary btn-view-product w-100"
                                            href="<?php echo esc_url($product_url); ?>"
                                    >
                                        <?php esc_html_e('View Product', 'wooshop'); ?>
                                    </a>

                                </div>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else : ?>

            <div class="alert alert-light border" role="status">
                <?php
                esc_html_e(
                    'Featured products will appear here when products are available.',
                    'wooshop'
                );
                ?>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php endif;