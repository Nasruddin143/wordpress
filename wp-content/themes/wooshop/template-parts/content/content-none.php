<?php
/**
 * No Content Template
 *
 * Displays an empty-content state.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section
        class="ws-error-state ws-error-state--empty"
        aria-labelledby="ws-no-results-title">

    <div class="ws-error-state__content">

        <h1
                id="ws-no-results-title"
                class="ws-error-state__title">

            <?php
            if ( is_search() ) {

                esc_html_e(
                        'Nothing Found',
                        'wooshop'
                );

            } else {

                esc_html_e(
                        'Nothing Found Here',
                        'wooshop'
                );
            }
            ?>

        </h1>

        <p class="ws-error-state__message">

            <?php
            if ( is_search() ) {

                esc_html_e(
                        'Sorry, but nothing matched your search. Please try again with different keywords.',
                        'wooshop'
                );

            } else {

                esc_html_e(
                        'There is currently no content available here.',
                        'wooshop'
                );
            }
            ?>

        </p>

        <?php if ( is_search() ) : ?>

            <div class="ws-error-search">

                <?php
                get_search_form();
                ?>

            </div>

        <?php endif; ?>

        <div class="ws-error-actions">

            <a
                    class="ws-button ws-button--primary"
                    href="<?php echo esc_url( home_url( '/' ) ); ?>">

                <?php
                esc_html_e(
                        'Back to Home',
                        'wooshop'
                );
                ?>

            </a>

        </div>

    </div>

</section>