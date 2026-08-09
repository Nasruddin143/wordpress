<?php
/**
 * Footer Newsletter
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<section
        class="ws-footer-newsletter"
        aria-labelledby="ws-footer-newsletter-title">

    <div class="container">
        <div class="ws-footer-newsletter-content">

            <h2
                    id="ws-footer-newsletter-title"
                    class="ws-footer-newsletter-title">

                <?php esc_html_e('Stay Updated', 'wooshop'); ?>

            </h2>

            <p class="ws-footer-newsletter-description">

                <?php
                esc_html_e(
                        'Subscribe to our newsletter for the latest products, offers and updates.',
                        'wooshop'
                );
                ?>

            </p>

        </div>

        <form
                class="ws-footer-newsletter-form"
                method="post"
                action="#"
                novalidate>

            <label
                    class="screen-reader-text"
                    for="ws-newsletter-email">

                <?php esc_html_e('Email Address', 'wooshop'); ?>

            </label>

            <input
                    type="email"
                    id="ws-newsletter-email"
                    name="email"
                    class="ws-newsletter-input"
                    placeholder="<?php esc_attr_e('Enter your email address', 'wooshop'); ?>"
                    autocomplete="email"
                    required>

            <button
                    type="submit"
                    class="ws-newsletter-submit">

                <?php esc_html_e('Subscribe', 'wooshop'); ?>

            </button>

        </form>
    </div>
</section>