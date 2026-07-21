<?php
/**
 * Template Name: Feedback Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package king_tailors
 */

use KT\Helpers\UI;

get_header(); ?>

<!-- Site Content -->
<main id="main-content" class="site-main">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <!-- Page Content Wrapper -->
    <div class="page-content-wrapper py-5">
        <div class="container">

            <header class="entry-header mb-4">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <!-- Breadcrumb -->
                    <?php if (function_exists('bcn_display')): ?>

                        <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                            <?php bcn_display(); ?>
                        </nav>

                    <?php endif; ?>

                    <!-- Social Share Buttons -->
                    <?php echo UI::social_share(); ?>
                </div>


                <!-- Page Title -->
                <h1 class="entry-title fw-bold"><?php the_title(); ?></h1>
                
            </header>

            <div class="entry-content">
                <div class="form-wrapper p-3 bg-light">
                    <div class="row">

                        <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2 col-sm-12 col-xs-12">
                            <?php $access_key = esc_attr(get_theme_mod('kt_web3forms_contact_key')); ?>

                            <form id="ktFeedbackForm" action="https://api.web3forms.com/submit" method="POST"
                                class="row g-3 needs-validation" novalidate>

                                <!-- Web3Forms Access Key -->
                                <input type="hidden" name="access_key" value="<?php echo $access_key; ?>">

                                <!-- Form Info -->
                                <input type="hidden" name="subject"
                                    value="<?php echo esc_attr('New Customer Feedback - King Tailors'); ?>">
                                <input type="hidden" name="from_name"
                                    value="<?php echo esc_attr('King Tailors Website'); ?>">
                                <input type="hidden" name="redirect" value="https://kingtailors.co.in/thank-you/">

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <?php echo esc_attr('Full Name'); ?>
                                    </label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="<?php echo esc_attr('Your Name'); ?>" required>
                                    <div class="invalid-feedback">
                                        <?php echo esc_attr('Please enter your full name'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <?php echo esc_attr('Mobile Number'); ?>
                                    </label>
                                    <input type="tel" class="form-control" name="phone" id="phone"
                                        placeholder="<?php echo esc_attr('Mobile Number'); ?>" pattern="[0-9]{10}"
                                        required>
                                    <div class="invalid-feedback">
                                        <?php echo esc_attr('10-digit mobile number'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">
                                        <?php echo esc_attr('Service Used'); ?>
                                    </label>
                                    <select name="service" id="service" class="form-select" required>
                                        <option value="">
                                            <?php echo esc_attr('-- Select Service --'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Tailoring'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Sewing Machine Sales'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Sewing Machine Repair'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Air Cooler Sales'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Air Cooler Repair'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('Spare Parts'); ?>
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?php echo esc_attr('Please select a service.'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">
                                        <?php echo esc_attr('Rating'); ?>
                                    </label>
                                    <select name="rating" id="rating" class="form-select" required>
                                        <option value="">
                                            <?php echo esc_attr('-- Select Rating --'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('★★★★★ Excellent'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('★★★★☆ Very Good'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('★★★☆☆ Good'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('★★☆☆☆ Average'); ?>
                                        </option>
                                        <option>
                                            <?php echo esc_attr('★☆☆☆☆ Poor'); ?>
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?php echo esc_attr('Please select a rating.'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">
                                        <?php echo esc_attr('Your Feedback'); ?>
                                    </label>
                                    <textarea name="message" class="form-control" id="message" rows="4"
                                        placeholder="<?php echo esc_attr('Write your feedback...'); ?>"
                                        required></textarea>
                                    <div class="invalid-feedback">
                                        <?php echo esc_attr('Please enter your feedback.'); ?>
                                    </div>
                                </div>

                                <!-- Anti-spam -->
                                <div class="mb-3">
                                    <div class="h-captcha" data-captcha="true"></div>
                                </div>
                                
                                <div class="">
                                    <button class="btn btn-dark rounded-pill px-3 py-2" type="submit">
                                    <?php echo esc_attr('Submit Feedback'); ?>
                                </button>
                                </div>
                                
                            </form>

                        </div>
                    </div>


                </div>

                <!-- <div class="bg-light">
                    <div class="card rounded-0">
                        <div class="row g-0">
                            
                            <div class="col-12 col-md-5 order-md-0 d-flex align-items-center bg-blue">
                                <div class="p-4 text-white text-center">
                                    <i class="bi bi-chat-left-text-fill fs-2"></i>
                                    <h2><?php //echo esc_html(esc_attr('Help us improve')); ?></h2>
                                    <p class="lead">
                                        <?php //echo esc_html(esc_attr('Please take a moment to let us know about your experience')); ?>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</main>

<?php
// get_sidebar();
get_footer();