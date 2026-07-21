<?php

$brands = get_terms(array(
    'taxonomy' => 'product_brand',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

if (!empty($brands) && !is_wp_error($brands)): ?>

    <div id="wcBranding" class="home-branding py-4">

        <div class="container text-center">
            <div class="brand-marquee">
                <div class="brand-track">

                    <?php
                    // Duplicate for seamless loop
                    foreach (array_merge($brands, $brands) as $brand):

                        $thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
                        $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: $brand->name;

                        $logo = wp_get_attachment_image_url($thumbnail_id, 'medium');

                        if (!$logo) {
                            continue;
                        }
                        ?>

                        <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="brand-item"
                            title="<?php echo esc_attr($brand->name); ?>">

                            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt_text); ?>" loading="lazy">

                        </a>

                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>

<?php endif; ?>