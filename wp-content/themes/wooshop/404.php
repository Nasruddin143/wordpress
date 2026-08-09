<?php
/**
 * 404 Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main
            id="primary"
            class="site-main">

        <div class="ws-container">

            <?php
            get_template_part(
                    'template-parts/content/content',
                    '404'
            );
            ?>

        </div>

    </main>

<?php
get_footer();