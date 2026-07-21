<?php

/**
 * The header for our theme (Bootstrap + SEO)
 *
 * Displays all of the <head> section and the site header with navigation.
 *
 * @package kt
 */

// $phone = get_theme_mod('kt_phone', '');
$phone = get_theme_mod('kt_phone', '');
$country_code = get_theme_mod('kt_country_code', '+91');

$email = get_theme_mod('kt_email', '');

$wa_link = 'https://wa.me/91' . get_theme_mod('kt_phone') . '?text=' . urlencode(
    __('Hello, I would like to inquire about your services')
);

?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="page" class="site">

        <!--  Topbar Section -->
        <div class="section-topbar top-bar border-bottom bg-light">

            <div class="container d-flex justify-content-between align-items-center">

                <div class="left-section me-auto">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="skip-link link-dark link-offset-2 link-underline link-underline-opacity-0"
                                href="#main-content" title="<?php esc_html_e('Skip to content', 'kt'); ?>"><?php esc_html_e('Skip to content', 'kt'); ?>
                            </a>
                        </li>
                    </ul>

                </div>

                <div class="right-section">
                    <ul class="nav py-2">

                        <li class="nav-item d-none d-lg-block px-2">
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="link-dark link-offset-2 link-underline link-underline-opacity-0" target="_blank" rel="noopener nofollow"
                            title="Email contact <?php bloginfo('name') ?>">
                                <img class="me-1" width="18" height="18"
                                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/mail.png'); ?>"
                                    alt="Email <?php bloginfo('name') ?>" title="Email <?php bloginfo('name') ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </li>

                        <li class="nav-item mx-2 d-none d-lg-block">
                            <div class="vr h-100"></div>
                        </li>

                        <li class="nav-item px-2">
                            <a href="<?php echo esc_url($wa_link); ?>" target="_blank" class="link-dark link-offset-2 link-underline link-underline-opacity-0" rel="noopener nofollow"
                            title="Call <?php bloginfo('name') ?>">
                                <img class="me-1" width="18" height="18"
                                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/phone-call-2.png'); ?>"
                                    alt="Call to <?php bloginfo('name') ?>" title="Call to <?php bloginfo('name') ?>">
                                <?php echo esc_html($country_code); ?>-<?php echo esc_html($phone); ?>
                            </a>
                        </li>

                    </ul>

                    <!-- <div class="vr mx-2"></div> -->
                </div>
            </div>
        </div>

        <!--  Header / Navbar -->
        <header id="masthead" class="site-header shadow sticky-top py-2" role="banner">

            <?php
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo_img = '';

            if ($custom_logo_id) {
                $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);

                // Fallback alt text
                if (empty($logo_alt)) {
                    $logo_alt = get_bloginfo('name');
                }

                $logo_img = wp_get_attachment_image(
                    $custom_logo_id,
                    'kt-site-logo',
                    false,
                    [
                        'class' => 'brand-logo',
                        'alt' => esc_attr($logo_alt),
                        'loading' => 'eager',
                        'fetchpriority' => 'high',
                        'title' => esc_attr($logo_alt),
                    ]
                );
            }
            ?>

            <!--  Navbar Section -->
            <nav id="main-navbar" class="navbar navbar-expand-lg p-0" role="navigation">
                <div class="container">
                    <!-- Navbar Brand -->
                    <div class="site-branding">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"
                            class="d-inline-flex align-items-center text-decoration-none">

                            <?php if ($logo_img): ?>
                                <div class="flex-shrink-0">
                                    <?php echo $logo_img; ?>
                                </div>
                            <?php endif; ?>

                            <div class="ms-3">
                                <?php if (is_front_page()): ?>
                                    <h1 class="lh-1 fs-4 mb-0 fw-bold text-uppercase text-danger"><?php bloginfo('name'); ?></h1>
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

                    <!-- Primary Navigation -->
                    <div class="navbar-collapse d-none d-lg-flex">
                        <?php
                        wp_nav_menu(
                            [
                                'theme_location' => 'primary',
                                'menu_id' => 'menu-primary-menu',
                                'container' => false,
                                'menu_class' => 'bs-nav',
                                'fallback_cb' => '__return_false',
                                'items_wrap' => '<ul id="%1$s" class="navbar-nav ms-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
                                'depth' => 2,
                                'walker' => new \KT\Helpers\Bootstrap_NavWalker()
                            ]
                        );
                        ?>
                        <!-- Expanding Search Icon -->

                        <div class="expanding-search position-relative ms-4">
                            <button class="search-toggle btn p-0 border-0 bg-transparent" type="button"
                                aria-label="<?php esc_attr_e('Open search', 'kt'); ?>" aria-expanded="false"
                                aria-controls="site-search">
                                <img class="img-fluid" alt="<?php esc_attr_e('Site search icon', 'kt'); ?>"
                                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/site-search.svg'); ?>"
                                    width="20" />
                                <!-- <i class="bi bi-search"></i> -->
                            </button>

                            <div id="site-search-form" class="search-wrapper">
                                <?php get_search_form(); ?>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-dark d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                </div>
            </nav>
        </header>

        <!-- Offcanvas Menu -->
        <div class="offcanvas offcanvas-start " tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header border-bottom align-items-start">
                <!-- Navbar Brand -->
                <div class="site-branding">
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

                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                    aria-label="<?php esc_attr_e('Close', 'kt'); ?>"></button>

            </div>
            <div class="offcanvas-body">
                <div class="search-wrapper pb-3 border-bottom">
                    <?php get_search_form(); ?>
                </div>

                <div class="menu-links mb-3">
                    <?php
                    wp_nav_menu(
                        [
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'bs-nav',
                            'fallback_cb' => '__return_false',
                            'items_wrap' => '<ul id="%1$s" class="navbar-nav mobile-nav ms-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
                            'depth' => 2,
                            'walker' => new \KT\Helpers\Bootstrap_NavWalker()
                        ]
                    );
                    ?>
                </div>

                <!-- SOCIAL LINKS -->
                <div class="offcanvas-social mt-4">
                    <?php $socials = ['facebook', 'instagram', 'twitter-x', 'linkedin', 'youtube']; ?>

                    <?php foreach ($socials as $social):
                        $url = get_theme_mod("kt_{$social}_url");
                        $icon = get_theme_mod("kt_{$social}_icon");

                        if ($url): ?>

                            <?php
                            $label = sprintf(
                                esc_html__('Follow us on %s', 'kt'),
                                ucfirst(str_replace('-', ' ', $social))
                            );
                            ?>

                            <a href="<?php echo esc_url($url); ?>" title="<?php echo esc_attr($label); ?>" target="_blank" rel="noopener nofollow" class="d-block"
                                aria-label="<?php echo esc_attr($label); ?>">
                                <i class="<?php echo esc_attr($icon); ?> fs-4 text-dark" area-hidden="true"></i>
                            </a>
                    <?php endif;
                    endforeach; ?>

                </div>

            </div>
        </div>