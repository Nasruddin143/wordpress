<?php

/**
 * Template part for displaying posts in card layout
 *
 * @package kt
 */

use KT\Helpers\Badges;
use KT\Helpers\Price;
use KT\Helpers\Ratings;
use KT\Helpers\Terms;

$post_id = get_the_ID(); ?>

<div class="col-md-4 col-lg-3 col-sm-12 mb-4">

    <!-- Product Card -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('card border-0 h-100'); ?>>

        <!-- Thumbnail -->
        <?php if (has_post_thumbnail()): ?>
            <div class="position-relative product-img-wrapper rounded-top">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                    <?php the_post_thumbnail('kt-product-main', [
                        'class' => 'product-image rounded-top img-fluid',
                        'alt' => the_title_attribute(['echo' => false]),
                        'title' => the_title_attribute(['echo' => false]),
                    ]); ?>
                </a>

                <?php if ($discount = Price::show_discount_percent($post_id)): ?>
                    <span class="discount-badge badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">
                        <?php echo esc_html(sprintf(__('%s%% OFF', 'kt'), $discount)); ?>
                    </span>
                <?php endif; ?>
                
                <div class="wishlist-btn">
                    <?php echo Badges::get($post_id); ?>    
                </div>
            </div>
        <?php else: ?>
            <div class="position-relative product-img-wrapper">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.webp'); ?>"
                        alt="<?php echo esc_html(__('Placeholder Image')); ?>"
                        title="<?php echo esc_html(__('No Image')); ?>" class="img-fluid w-100">
                </a>

                <?php if ($discount = Price::show_discount_percent($post_id)): ?>
                    <span class="discount-badge badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">
                        <?php echo esc_html(sprintf(__('%s%% OFF', 'kt'), $discount)); ?>
                    </span>
                <?php endif; ?>
                <div class="wishlist-btn">
                    <?php echo Badges::get($post_id); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card-body px-0">

            <!-- Category -->
            <div class="product-terms mb-2">

                <?php
                $primary_term = Terms::get_primary_term($post_id, get_post_type());

                if ($primary_term) : ?>
                    <a class="link-offset-2 link-underline link-underline-opacity-0 link-success" href="<?php echo esc_url(get_term_link($primary_term)); ?>"
                        title="<?php echo esc_html($primary_term->name); ?>">
                        <?php echo esc_html($primary_term->name); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Title -->
            <?php if (is_singular()): ?>
                <?php the_title('<h2 class="card-title fw-semibold h6 mb-2">', '</h2>'); ?>
            <?php else: ?>

                <h2 class="card-title fw-bold h6 mb-2">
                    <a href="<?php esc_url(the_permalink()); ?>" class="link-offset-2 link-underline link-underline-opacity-0 link-dark"
                        title="<?php echo esc_attr(get_the_title()); ?>">
                        <?php echo esc_attr(get_the_title()); ?>
                    </a>
                </h2>

            <?php endif; ?>

            <div class="d-sm-flex align-items-center justify-content-between">
                <!-- Product Pricing -->
                <?php
                echo Price::render($post_id, [
                    'show_discount' => true,
                    'show_diff' => false,
                    'currency' => '₹',
                ]);
                ?>

                <!-- Ratings -->
                <?php echo Ratings::rating_stars($post_id); ?>
            </div>
        </div>
    </article>
</div>