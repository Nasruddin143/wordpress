<?php
defined('ABSPATH') || exit;

use KT\Features\WooCommerceQuery;

$terms = WooCommerceQuery::brands(12, 'product_brand');

if (empty($terms) || is_wp_error($terms)) return;
?>

<div class="kt-brand-marquee">

    <div class="kt-marquee-track py-3">

        <?php
        // duplicate loop for infinite effect
        for ($i = 0; $i < 2; $i++):
            foreach ($terms as $term):

                $thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        ?>

                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="kt-brand-item">

                    <?php if ($thumb_id): ?>
                        <?php echo wp_get_attachment_image(
                            $thumb_id,
                            'medium',
                            false,
                            [
                                'class' => 'kt-brand-img img-fluid',
                                'loading' => 'lazy',
                                'alt' => esc_attr($term->name)
                            ]
                        ); ?>
                    <?php endif; ?>

                </a>

        <?php endforeach;
        endfor; ?>

    </div>

</div>