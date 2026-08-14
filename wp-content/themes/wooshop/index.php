<?php
/**
 * Main Template
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

            <?php if ( have_posts() ) : ?>

                <div class="ws-content-list">

                    <?php
                    wooshop_render_loop();
                    ?>

                </div>

                <?php
                get_template_part(
                        'template-parts/navigation/pagination'
                );
                ?>

            <?php else : ?>

                <?php
                get_template_part(
                        'template-parts/content/content',
                        'none'
                );
                ?>

            <?php endif; ?>

        </div>

    </main>

<?php
get_footer();