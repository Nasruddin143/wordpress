<?php
/**
 * 404 Template
 *
 * Displays the page-not-found state.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="container py-5">

        <section class="ws-404 text-center py-5">

            <header class="page-header mb-4">

                <p class="display-1 fw-bold mb-2">
                    404
                </p>

                <h1 class="page-title h2">
                    <?php esc_html_e( 'Page not found', 'wooshop' ); ?>
                </h1>

            </header>

            <div class="page-content">

                <p class="text-body-secondary">
                    <?php
                    esc_html_e(
                            'The page you are looking for could not be found.',
                            'wooshop'
                    );
                    ?>
                </p>

                <a
                        class="btn btn-primary"
                        href="<?php echo esc_url( home_url( '/' ) ); ?>"
                >
                    <?php esc_html_e( 'Back to home', 'wooshop' ); ?>
                </a>

            </div>

        </section>

    </div>

<?php
get_footer();