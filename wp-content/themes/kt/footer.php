<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kt
 */


$custom_logo_id = get_theme_mod('custom_logo');
$logo_img = '';

if ($custom_logo_id) {
    $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);

    // Fallback alt text
    if (empty($logo_alt)) {
        $logo_alt = get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }
    $logo_img = wp_get_attachment_image(
        $custom_logo_id,
        'kt-site-logo',
        false,
        [
            'class' => 'brand-logo',
            'alt' => esc_attr($logo_alt),
            'title' => esc_attr($logo_alt),
            'loading' => 'eager',
            'fetchpriority' => 'high',
        ]
    );
}

$address =  get_theme_mod('kt_address');
$open =  get_theme_mod('kt_opening_time');
$holiday =  get_theme_mod('kt_holiday');
$phone =  get_theme_mod('kt_phone');
$country_code =  '+91';
$email =  get_theme_mod('kt_email');
?>

<!-- Site Footer -->
<footer id="colophon" class="site-footer pt-5">
    <!-- Site Logo and Social Icons -->
    <div class="footer-top-section">
        <div class="container border-bottom pb-4">
            <div class="row gy-3 align-items-sm-center">
                <div class="col-12 col-sm-6">
                    <div class="site-branding footer-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"
                            class="d-inline-flex align-items-center text-decoration-none">

                            <?php if ($logo_img): ?>
                                <div class="flex-shrink-0">
                                    <?php echo $logo_img; ?>
                                </div>
                            <?php endif; ?>

                            <div class="ms-3">
                                <?php if (is_front_page()): ?>
                                    <div class="lh-1 fs-4 mb-0 fw-bold text-uppercase text-danger"><?php bloginfo('name'); ?></div>
                                <?php else: ?>
                                    <div class="lh-1 fs-4 mb-0 fw-bold text-uppercase text-danger"><?php bloginfo('name'); ?></div>
                                <?php endif; ?>

                                <?php if (get_bloginfo('description')): ?>
                                    <span class="brand-tagline text-uppercase d-block text-dark">
                                        <?php bloginfo('description'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>

                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <?php $socials = ['facebook', 'instagram', 'twitter-x', 'linkedin', 'youtube']; ?>

                    <div class="social-media-wrapper d-flex justify-content-sm-end gap-2">
                        <div class="d-flex gap-3 m-0 list-unstyled">
                            <?php foreach ($socials as $social):
                                $url = get_theme_mod("kt_{$social}_url");
                                $icon = get_theme_mod("kt_{$social}_icon");

                                if ($url): ?>

                                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener nofollow"
                                        class="link-offset-2 link-underline link-underline-opacity-0 link-secondary" aria-label="<?php
                                                                                                                                    echo esc_attr(
                                                                                                                                        sprintf(
                                                                                                                                            __('Follow us on %s'),
                                                                                                                                            __(ucfirst(str_replace('-', ' ', $social)))
                                                                                                                                        )
                                                                                                                                    );
                                                                                                                                    ?>" title="<?php
                                                                                                                                                echo esc_attr(
                                                                                                                                                    sprintf(
                                                                                                                                                        __('Visit our %s page'),
                                                                                                                                                        __(ucfirst(str_replace('-', ' ', $social)))
                                                                                                                                                    )
                                                                                                                                                );
                                                                                                                                                ?>">
                                        <i class="<?php echo esc_attr($icon); ?> fs-4 me-1" area-hidden="true"></i>
                                    </a>

                            <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WP Site Links -->
    <div class="footer-middle-section py-5">
        <div class="container">
            <div class="row gy-4 gy-md-0">
                <?php
                $footer_sections = [
                    [
                        'title' => __('Air Coolers'),
                        'menu'  => 'cooler'
                    ],
                    [
                        'title' => __('Sewing Machines'),
                        'menu'  => 'sewing'
                    ],
                    [
                        'title' => __('Accessories'),
                        'menu'  => 'accessory'
                    ]
                ];
                ?>

                <?php foreach ($footer_sections as $section): ?>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="link-wrapper">
                            <h4 class="mb-3 fw-bold h5 text-dark">
                                <?php echo esc_html($section['title']); ?>
                            </h4>

                            <?php
                            wp_nav_menu([
                                'theme_location' => $section['menu'],
                                'container'      => false,
                                'menu_class'     => 'list-unstyled mb-0',
                                'fallback_cb'    => false
                            ]);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="link-wrapper">
                        <h4 class="mb-3 fw-bold h5 text-dark">
                            <?php echo esc_html('Contact Us'); ?>
                        </h4>

                        <address class="mb-0">
                            <p class="mb-2 text-uppercase"><strong><?php bloginfo('name'); ?></strong></p>
                            <p class="mb-3"><?php echo esc_html($address); ?></p>
                            <p class="mb-0">
                                <span class="me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="#2C69CB" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-phone-call">
                                        <path
                                            d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                </span>
                                <strong>Phone: </strong> <a class="link-offset-2 link-underline link-underline-opacity-0 link-dark" href="tel:<?php echo esc_html($country_code); ?><?php echo esc_html($phone); ?>"><?php echo esc_html($country_code); ?><?php echo esc_html($phone); ?></a>
                            </p>
                            <p class="mb-3">
                                <span class="me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="#2C69CB" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-mail">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                        </path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <strong>Email: </strong><a class="link-offset-2 link-underline link-underline-opacity-0 link-dark" href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a>
                            </p>

                            <p class="mb-0"><strong>Opening Hours: </strong><?php echo esc_html($open); ?><br><?php echo esc_html($holiday); ?></p>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright and Other Links -->
    <div class="footer-bottom-section text-light">
        <div class="container py-3">
            <div class="row gy-3 align-items-lg-center">
                <div class="col-12 col-lg-6 order-1 order-lg-0">
                    <div class="copyright-wrapper d-block mb-1 fs-7 text-start">
                        <a href="<?php echo esc_url(home_url('/')) ?>" title="<?php echo get_bloginfo('name') . ' - ' . get_bloginfo('description'); ?>"
                            class="link-offset-2 link-underline link-underline-opacity-0 link-light"><?php echo esc_html(get_bloginfo('name')); ?></a> ©
                        <?php echo esc_html(date("Y")); ?>
                        <?php echo esc_html('All Rights Reserved'); ?>.
                    </div>

                    <div class="credit-wrapper d-block fs-8 text-start small">
                        <time datetime="<?php echo get_the_modified_date('c'); ?>">
                            <?php echo esc_html('Last updated:'); ?> <?php echo get_the_modified_date('M j, Y'); ?>
                        </time>
                    </div>

                </div>

                <div class="col-12 col-lg-6">
                    <div class="link-wrapper">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer_legal',
                            'container' => false,
                            'menu_class' => 'd-flex justify-content-end gap-3 list-unstyled mb-0',
                            'fallback_cb' => false,
                            'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>

</div>

<!-- Floating WhatsApp Navigation -->
<a href="https://wa.me/91<?php echo esc_html(get_theme_mod('kt_phone')) ?>?text=<?php echo urlencode('Hello, I would like to inquire about your services'); ?>" class="sticky-whatsapp" target="_blank" rel="noopener nofollow"
    aria-label="<?php echo esc_attr('Chat with King Tailors Shop on WhatsApp'); ?>"
    title="<?php echo esc_attr('Chat with King Tailors Shop on WhatsApp'); ?>">
    <i class="bi bi-whatsapp" aria-hidden="true"></i>
</a>

<button id="kt-go-top" class="kt-go-top" aria-label="<?php echo esc_attr('Go to top', 'kt'); ?>">
    <i class="bi bi-chevron-up"></i>
</button>

<!-- WP Scripts Section -->
<?php wp_footer(); ?>

</body>

</html>