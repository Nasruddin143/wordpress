<?php
/**
 * Archive Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main id="primary" class="site-main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">

                <?php
                the_archive_title(
                        '<h1 class="page-title">',
                        '</h1>'
                );

                the_archive_description(
                        '<div class="archive-description">',
                        '</div>'
                );
                ?>

            </header>

            <div class="archive-posts">

                <?php
                while ( have_posts() ) :

                    the_post();

                    get_template_part(
                            'template-parts/content/content',
                            get_post_type()
                    );

                endwhile;
                ?>

            </div>

            <?php
            wooshop_pagination();
            ?>

        <?php else : ?>

            <?php
            get_template_part(
                    'template-parts/content/content',
                    'none'
            );
            ?>

        <?php endif; ?>

    </main>

<?php
get_sidebar();
get_footer();