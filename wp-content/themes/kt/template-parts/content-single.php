<?php

/**
 * Template Part: Single Product Layout
 */

use KT\Helpers\Price;
use KT\Helpers\Ratings;
use KT\Helpers\Terms;
use KT\Modules\FAQ;
use KT\Modules\RelatedPosts;

defined('ABSPATH') || exit;

$post_id = get_the_ID();
$post_type = get_post_type($post_id);

//$primary_term = kt_get_primary_term($post_id);
$post_tags = Terms::get_post_tags($post_id);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-product mb-5'); ?>>

    <div class="row mb-4">
        <div class="col-md-12">
            <!-- Product Card -->
            <div class="card border-0 shadow">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-4">
                            <div class="product-img-card border rounded p-3 h-100">

                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail(
                                        'kt-product-main',
                                        [
                                            'class' => 'img-fluid wp-post-image',
                                            'loading' => 'lazy',
                                            'title' => esc_attr(get_the_title()),
                                            'alt' => esc_attr(get_the_title()),
                                            'width' => 800,
                                            'height' => 800,
                                            'fetchpriority' => 'low',
                                        ]
                                    ); ?>
                                <?php else: ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.webp'); ?>"
                                        class="img-fluid rounded-0 border-0"
                                        loading="lazy"
                                        alt="<?php echo esc_attr(get_the_title()); ?>"
                                        title="<?php echo esc_attr(get_the_title()); ?>">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="col-md-5">

                            <header class="entry-header pt-3 pb-4">
                                <h1 class="entry-title h3 fw-bold mb-3"><?php the_title(); ?></h1>

                                <!-- Ratings -->
                                <div class="product-ratings">
                                    <?php echo Ratings::ratings_full($post_id); ?>
                                </div>
                            </header>

                            <!-- Price -->
                            <div class="product-price pb-3">
                                <div class="price-card border rounded p-3 h-100">

                                    <?php echo Price::render($id, [
                                        'show_discount' => true,
                                        'show_diff' => true
                                    ]); ?>
                                </div>

                            </div>

                            <!-- Excerpt -->
                            <?php if (has_excerpt()): ?>
                                <div class="entry-summary mb-4">
                                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="d-flex flex-wrap gap-2 mb-4">

                                <?php
                                $phone = get_theme_mod('kt_phone');

                                $message = sprintf(
                                    __('Hello, I would like to inquire about %s'),
                                    get_the_title()
                                );
                                ?>

                                <?php if (!empty($phone)) : ?>
                                    <a href="<?php echo esc_url('https://wa.me/' . $phone . '?text=' . urlencode($message)); ?>"
                                        class="btn btn-dark rounded-pill px-3" title="Buy <?php echo esc_attr(get_the_title()); ?> on WhatsApp"
                                        target="_blank" rel="noopener noreferrer nofollow">
                                        <i class="bi bi-whatsapp me-2"></i>
                                        <?php echo esc_html('Order on WhatsApp'); ?>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-outline-dark rounded-pill px-3" type="button"
                                    aria-label="<?php echo esc_attr('Add to Favourites'); ?>">
                                    <i class="bi bi-heart me-1"></i>
                                    <?php echo esc_html('Add to Favourites'); ?>
                                </button>

                            </div>


                            <div class="row g-3 product-trust mb-4 text-center text-muted">

                                <div class="col-4">
                                    <div class="trust-card border rounded p-3 h-100">

                                        <i class="bi bi-truck fs-2"></i>
                                        <div class="small fw-semibold mb-2">Free Delivery</div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="trust-card border rounded p-3 h-100">

                                        <i class="bi bi-shield-check fs-2"></i>
                                        <div class="small fw-semibold mb-2">Secure Payment</div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="trust-card border rounded p-3 h-100">

                                        <i class="bi bi-cash-coin fs-2"></i>
                                        <div class="small fw-semibold mb-2">COD Available</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- Category -->
                            <div class="product-terms mb-2">
                                <?php
                                $primary_term = Terms::get_primary_term($post_id, get_post_type());

                                if ($primary_term): ?>
                                    <a href="<?php echo esc_url(get_term_link($primary_term)); ?>"
                                        class="link-offset-2 link-underline link-underline-opacity-0 text-secondary small text-uppercase fw-bold"
                                        title="<?php echo esc_html($primary_term->name); ?>">
                                        <?php echo esc_html($primary_term->name); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Product Details Card -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body p-4">

                    <!-- Nav Tabs -->
                    <ul class="nav nav-pills mb-3 pb-3 border-bottom" id="productTab-<?php echo esc_attr($post_id); ?>" role="tablist">

                        <!-- Product Details Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fs-body"
                                id="overview-tab-<?php echo esc_attr($post_id); ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#overview-<?php echo esc_attr($post_id); ?>"
                                type="button"
                                role="tab">
                                Product Details
                            </button>
                        </li>

                        <!-- Similar Features Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fs-body"
                                id="features-tab-<?php echo esc_attr($post_id); ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#features-<?php echo esc_attr($post_id); ?>"
                                type="button"
                                role="tab">
                                Similar Features
                            </button>
                        </li>

                        <!-- Customer Reviews Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fs-body"
                                id="reviews-tab-<?php echo esc_attr($post_id); ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#reviews-<?php echo esc_attr($post_id); ?>"
                                type="button"
                                role="tab">
                                Customer Reviews
                            </button>
                        </li>

                        <!-- Write a Review Tab -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fs-body"
                                id="write-tab-<?php echo esc_attr($post_id); ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#write-<?php echo esc_attr($post_id); ?>"
                                type="button"
                                role="tab">
                                Write a Review
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="productTabContent-<?php echo esc_attr($post_id); ?>">

                        <!-- Product Details -->
                        <div class="tab-pane fade show active entry-content"
                            id="overview-<?php echo esc_attr($post_id); ?>"
                            role="tabpanel">

                            <?php the_content(); ?>

                        </div>

                        <!-- Similar Features -->
                        <div class="tab-pane fade"
                            id="features-<?php echo esc_attr($post_id); ?>"
                            role="tabpanel">

                            <?php if (!empty($post_tags)): ?>
                                <div class="post-tags mt-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($post_tags as $tag): ?>
                                            <a href="<?php echo esc_url(get_term_link($tag)); ?>"
                                                class="badge d-flex px-3 py-2 align-items-center text-secondary-emphasis bg-secondary-subtle rounded-pill text-decoration-none"
                                                rel="tag"
                                                aria-label="<?php echo esc_attr($tag->name); ?>"
                                                title="<?php echo esc_html($tag->name); ?>">

                                                <?php echo esc_html($tag->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <!-- Customer Reviews -->
                        <div class="tab-pane fade"
                            id="reviews-<?php echo esc_attr($post_id); ?>"
                            role="tabpanel">

                            <?php if (!empty($reviews->reviews)): ?>

                                <div class="review-list">

                                    <?php foreach ($reviews->reviews as $review):

                                        $rating = $review->rating;
                                        // Star display
                                        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); ?>

                                        <div class="review-item mb-3">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/kt-avatar-placeholder.png') ?>" alt="avatar">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0 fw-bold"><?php echo esc_html($review->author); ?></h6>
                                                    <div class="stars"><?php echo $stars; ?> <span class="text-muted fs-6"><?php echo date('d M Y', strtotime($review->date)); ?></span></div>
                                                    <?php echo esc_html($review->content); ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif;
                            ?>

                        </div>

                        <!-- Write a Review -->
                        <div class="tab-pane fade"
                            id="write-<?php echo esc_attr($post_id); ?>"
                            role="tabpanel">
                            <?php
                            echo do_shortcode('[site_reviews_form assigned_posts="' . $post_id . '"]');
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <?php echo FAQ::render(); ?>

        </div>
    </div>

    <!-- Related -->
    <div class="row mb-4">
        <?php echo (new RelatedPosts($post_id, $post_type, 4))->render(); ?>
    </div>

</article>