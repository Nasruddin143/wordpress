<?php
/**
 * 404 Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main id="primary" class="site-main error-404">

        <header class="page-header">

            <h1 class="page-title">

                <?php esc_html_e( 'Page Not Found', 'wooshop' ); ?>

            </h1>

        </header>

        <div class="page-content">

            <p>

                <?php
                esc_html_e(
                        'The page you are looking for does not exist.',
                        'wooshop'
                );
                ?>

            </p>

            <?php get_search_form(); ?>

        </div>

    </main>

<?php
get_footer();