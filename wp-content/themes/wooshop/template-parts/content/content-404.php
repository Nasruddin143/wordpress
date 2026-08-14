<?php
/**
 * 404 Content
 *
 * Reusable not-found content state.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section
        class="ws-error-state"
        aria-labelledby="ws-error-title">

    <div class="ws-error-state__content">

        <p
                class="ws-error-state__code"
                aria-hidden="true">
            <?php
            esc_html_e(
                    '404',
                    'wooshop'
            );
            ?>
        </p>

        <h1
                id="ws-error-title"
                class="ws-error-state__title">

            <?php
            esc_html_e(
                    'Page Not Found',
                    'wooshop'
            );
            ?>

        </h1>

        <p class="ws-error-state__message">

            <?php
            esc_html_e(
                    'Sorry, the page you are looking for could not be found.',
                    'wooshop'
            );
            ?>

        </p>

        <?php
        get_template_part(
                'template-parts/error/error-actions'
        );
        ?>

    </div>

</section>