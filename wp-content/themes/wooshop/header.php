<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WooShop
 */

$custom_logo_id = get_theme_mod('custom_logo');
$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
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
        <a class="skip-link screen-reader-text" title="Skip to main content"
            href="#primary"><?php esc_html_e('Skip to content', 'wooshop'); ?></a>

        <div class="top-header border border-bottom py-1">
            <div class="container d-flex align-items-center justify-content-between">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                        class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2">
                        <path
                            d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>

                    <a href="tel:<?php echo get_theme_mod('mobile_contact_number'); ?>"
                        class="link-offset-2 link-underline link-underline-opacity-0"><?php echo get_theme_mod('mobile_contact_number'); ?></a></span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                        class="main-grid-item-icon  me-2" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>

                    <a href="mailto:<?php echo get_theme_mod('email_contact_address'); ?>"
                        class="link-offset-2 link-underline link-underline-opacity-0"><?php echo get_theme_mod('email_contact_address'); ?></a></span>
            </div>
        </div>

        <header id="masthead" class="site-header">
            <nav class="navbar navbar-expand-lg bg-white py-0" data-bs-theme="light">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">

                        <?php if (has_custom_logo()): ?>

                            <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo get_bloginfo('name'); ?>"
                                class="custom-logo img-fluid" />

                        <?php else: ?>

                            <p class="fs-4 fw-bold mb-0 text-uppercase"><?php bloginfo('name'); ?></p>

                        <?php endif; ?>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarColor01">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => '',
                            'fallback_cb' => '__return_false',
                            'items_wrap' => '<ul id="%1$s" class="navbar-nav mx-auto %2$s">%3$s</ul>',
                            'depth' => 2,
                            'walker' => new bootstrap_5_wp_nav_menu_walker()
                        ));
                        ?>
                    </div>

                    <div class="d-flex align-items-center gap-4">

                        <!-- Wishlist -->
                        <a href="/wishlist" class="header-icon text-decoration-none text-dark text-center">
                            <div class="position-relative d-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                    class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>

                                <span
                                    class="badge rounded-pill bg-first position-absolute top-0 start-100 translate-middle">
                                    0
                                </span>
                            </div>
                            <div class="small mt-1">Wishlist</div>
                        </a>

                        <!-- Cart -->
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
                            class="header-icon text-decoration-none text-dark text-center">
                            <div class="position-relative d-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                    class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="20" cy="21" r="1" />
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                </svg>

                                <span
                                    class="badge rounded-pill bg-first position-absolute top-0 start-100 translate-middle cart-count">
                                    <?php echo WC()->cart->get_cart_contents_count(); ?>
                                </span>
                            </div>
                            <div class="small fw-normal mt-1">Cart</div>
                        </a>

                        <!-- Account -->
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"
                            class="header-icon text-decoration-none text-dark text-center">
                            <div class="position-relative d-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                    class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>

                            </div>
                            <div class="small fw-normal mt-1">Account</div>
                        </a>

                    </div>
                </div>
            </nav>
        </header><!-- #masthead -->