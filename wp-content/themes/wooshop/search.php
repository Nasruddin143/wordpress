<?php
/**
 * Search Results Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main id="primary" class="site-main">

        <header class="page-header">

            <h1 class="page-title">

                <?php
                printf(
                        esc_html__( 'Search Results for: %s', 'wooshop' ),
                        '<span>' . esc_html( get_search_query() ) . '</span>'
                );
                ?>

            </h1>

        </header>

        <?php if ( have_posts() ) : ?>

            <div class="search-results">

                <?php
                while ( have_posts() ) :

                    the_post();

                    get_template_part(
                            'template-parts/content/content',
                            'search'
                    );

                endwhile;
                ?>

            </div>

            <?php wooshop_pagination(); ?>

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