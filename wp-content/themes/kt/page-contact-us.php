<?php

/**
 * Template Name: Contact Us Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kt
 */

use KT\Helpers\UI;

get_header();

$address = get_theme_mod('kt_address');

$open = get_theme_mod('kt_opening_time');

$holiday = get_theme_mod('kt_holiday');

$phone = get_theme_mod('kt_phone');

$country_code = '+91';

$email = get_theme_mod('kt_email');

/* ---------------------------------------------------------
 * Helper: Polylang-safe theme mod
 * --------------------------------------------------------- */
?>

<main id="main-content" class="site-main">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <!--  HEADER  -->
            <header class="entry-header mb-4">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <!-- Breadcrumbs (Language-aware automatically via Polylang) -->
                    <?php if (function_exists('bcn_display')): ?>
                        <nav class="breadcrumb-nav mb-0" aria-label="<?php esc_attr_e('Breadcrumb', 'kt'); ?>">
                            <?php bcn_display(); ?>
                        </nav>
                    <?php endif; ?>

                    <!-- Social Share -->
                    <?php echo UI::social_share(); ?>

                </div>

                <h1 class="entry-title fw-bold"> <?php the_title(); ?> </h1>

                <p>
                    Thank you for visiting the Contact King Tailors page, your trusted <a href="<?php echo esc_url(home_url('/')); ?>">tailor shop in Ozar, Nashik.</a>
                    If you are looking for <a href="<?php echo esc_url(home_url('/services')); ?>">professional mens tailor
                        service</a>, <a href="<?php echo esc_url(home_url('/sewing-machines')); ?>">sewing machine</a> and <a
                        href="<?php echo esc_url(home_url('/air-coolers')); ?>">air cooler</a> sales and service, our team
                    is ready to assist you. Whether you need custom stitching, <a
                        href="<?php echo esc_url(home_url('/machine-accessories')); ?>">sewing machine
                        accessories</a>, or <a
                        href="<?php echo esc_url(home_url('/cooler-accessories')); ?>">air cooler spare
                        parts</a>,
                    feel free to contact us or visit our shop. We are happy to help customers from Ozar, Nashik, and
                    nearby areas.
                </p>

            </header>

            <!--  CONTENT  -->
            <div class="entry-content">

                <!--  CONTACT INFO  -->
                <div class="row mb-5 align-items-stretch">

                    <!-- Location -->
                    <div class="col-lg-4 d-flex">
                        <div class="card kt-contact-card border-0 rounded-0 h-100 w-100 mb-4">
                            <div class="card-body">
                                <div class="kt-icon-box mb-3">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>
                                    <h2 class="h4 fw-bold text-blue mb-2">
                                        <?php echo esc_html__(__('Our Location'), 'kt'); ?>
                                    </h2>
                                    <p class="mb-0">
                                        <?php echo nl2br(esc_html($address)); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="col-lg-4 d-flex">
                        <div class="card kt-contact-card border-0 rounded-0 h-100 w-100 mb-4">
                            <div class="card-body">
                                <div class="kt-icon-box mb-3"><i class="bi bi-phone-vibrate"></i></div>

                                <div>
                                    <h2 class="h4 fw-bold text-blue mb-2">
                                        <?php echo esc_html__(__('Connect Us'), 'kt'); ?>
                                    </h2>

                                    <p class=" mb-0">
                                        <a class="text-decoration-none text-blue"
                                            href="https://wa.me/91<?php echo esc_html(get_theme_mod('kt_phone')); ?>?text=<?php echo urlencode(__('Hello, I would like to inquire about your services')); ?>"
                                            target="_blank">
                                            <?php echo esc_html($country_code); ?> <?php echo esc_html($phone); ?>
                                        </a>
                                    </p>

                                    <p class="mb-0">
                                        <a class="text-decoration-none text-dark"
                                            href="mailto:<?php echo esc_html('kt_email'); ?>">
                                            <?php echo esc_html($email); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div class="col-lg-4 d-flex">
                        <div class="card kt-contact-card border-0 rounded-0 h-100 w-100 mb-4">
                            <div class="card-body">
                                <div class="kt-icon-box mb-3"><i class="bi bi-alarm"></i></div>

                                <div>
                                    <h2 class="h4 fw-bold text-blue mb-2">
                                        <?php echo esc_html__(__('Opening Hours'), 'kt'); ?>
                                    </h2>
                                    <p class="mb-0">
                                        <?php echo esc_html($open); ?>
                                    </p>
                                    <p class="mb-0">
                                        <?php echo esc_html($holiday); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--  FORM + MAP  -->
                <div class="row mb-5 align-items-stretch">

                    <!-- Contact Form -->
                    <div class="col-lg-6 mb-4">
                        <div class="card kt-form-card border-0 rounded-0 h-100 w-100">
                            <div class="card-body p-5">
                                <h2 class="h4 card-title fw-bold text-center">Send Us a Message</h3>
                                <p class="card-text text-center">If you have any questions <a
                                        href="<?php echo esc_url(home_url('/about-king-tailors')); ?>">about us</a> or our tailoring services, sewing
                                    machines, or air cooler repairs, feel free to call/mail us during business hours.
                                </p>

                                <hr>

                                <form id=" ktContactForm" action="https://api.web3forms.com/submit" method="POST"
                                    class="row g-3 needs-validation" novalidate>

                                    <input type="hidden" name="access_key"
                                        value="<?php echo esc_attr(get_theme_mod('kt_web3forms_contact_key')); ?>">

                                    <input type="hidden" name="success_message"
                                        value="<?php esc_html_e(__('Thank you! Your message has been sent successfully.'), 'kt'); ?>">

                                    <input type="hidden" name="error_message"
                                        value="<?php esc_attr_e(__('Oops! Something went wrong. Please try again.'), 'kt'); ?>">

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <?php esc_html_e(__('Your Name'), 'kt'); ?> *
                                        </label>
                                        <input type="text" name="name" class="form-control" required minlength="3"
                                            placeholder="<?php esc_html_e(__('Your Name.'), 'kt'); ?>">
                                        <div class="invalid-feedback">
                                            <?php esc_html_e(__('Please enter your name.'), 'kt'); ?>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <?php esc_html_e(__('Email'), 'kt'); ?> *
                                        </label>
                                        <input type="email" name="email" class="form-control" required
                                            placeholder="<?php esc_html_e(__('Email Address'), 'kt'); ?>">
                                        <div class="invalid-feedback">
                                            <?php esc_html_e(__('Please enter a valid email address.'), 'kt'); ?>
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <div class="col-12">
                                        <label class="form-label">
                                            <?php esc_html_e(__('Subject'), 'kt'); ?> *
                                        </label>
                                        <input type="text" name="subject" class="form-control" required
                                            placeholder="<?php esc_html_e(__('Subject'), 'kt'); ?>">
                                        <div class="invalid-feedback">
                                            <?php esc_html_e(__('Please enter a subject.'), 'kt'); ?>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div class="col-12">
                                        <label class="form-label">
                                            <?php esc_html_e(__('Message'), 'kt'); ?> *
                                        </label>
                                        <textarea name="message" class="form-control" rows="4" required minlength="10"
                                            placeholder="<?php esc_html_e(__('Your Message'), 'kt'); ?>"></textarea>
                                        <div class="invalid-feedback">
                                            <?php esc_html_e(__('Message must be at least 10 characters.'), 'kt'); ?>
                                        </div>
                                    </div>

                                    <!-- Captcha -->
                                    <div class="col-12">
                                        <div class="h-captcha" data-captcha="true"></div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-lg btn-outline-dark rounded-pill px-3">
                                            <?php esc_html_e(__('Send Message'), 'kt'); ?>
                                            <i class="bi bi-send-fill ms-2"></i>
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Google Map -->
                    <div class="col-lg-6 mb-4">
                        <div class="card kt-map-card border-0 rounded-0 h-100 w-100">
                            <div class="card-body p-0">

                                <?php $map_url = get_theme_mod('kt_google_map_embed'); ?>

                                <?php if ($map_url): ?>
                                    <div class="ratio ratio-1x1">
                                        <iframe src="<?php echo esc_url($map_url); ?>" loading="lazy" allowfullscreen
                                            referrerpolicy="no-referrer-when-downgrade"
                                            aria-label="<?php esc_attr_e('Google Map Location', 'kt'); ?>">
                                        </iframe>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</main>

<?php get_footer();
