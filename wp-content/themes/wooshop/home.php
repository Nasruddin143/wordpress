<?php
/**
 * Blog Home Template
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
                if ( is_home() && ! is_front_page() ) {
                    printf(
                            '<h1 class="page-title">%s</h1>',
                            esc_html( single_post_title( '', false ) )
                    );
                }
                ?>

            </header>

            <?php
            while ( have_posts() ) :

                the_post();

                get_template_part(
                        'template-parts/content/content',
                        get_post_type()
                );

            endwhile;

            wooshop_pagination();

        else :

            get_template_part(
                    'template-parts/content/content',
                    'none'
            );

        endif;
        ?>

    </main>

<?php
get_sidebar();
get_footer();