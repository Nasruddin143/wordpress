<?php
/**
 * No Content Found
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="no-results not-found">

    <header class="page-header">

        <h1 class="page-title">

            <?php esc_html_e( 'Nothing Found', 'wooshop' ); ?>

        </h1>

    </header>

    <div class="page-content">

        <p>

            <?php
            esc_html_e(
                'Sorry, no content matched your request.',
                'wooshop'
            );
            ?>

        </p>

        <?php get_search_form(); ?>

    </div>

</section>