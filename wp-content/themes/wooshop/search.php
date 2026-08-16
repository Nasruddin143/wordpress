<?php
/**
 * Search Results Template
 *
 * Displays WordPress search results.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="container py-5">

        <header class="ws-search-header mb-5">

            <h1 class="page-title">
                <?php
                printf(
                /* translators: %s: search query. */
                    esc_html__( 'Search results for: %s', 'wooshop' ),
                    '<span>' . esc_html( get_search_query() ) . '</span>'
                );
                ?>
            </h1>

        </header>

        <?php if ( have_posts() ) : ?>

            <div class="ws-search-results">

                <?php while ( have_posts() ) : ?>

                    <?php the_post(); ?>

                    <?php
                    get_template_part(
                        'template-parts/content/content-search'
                    );
                    ?>

                <?php endwhile; ?>

            </div>

            <?php
            get_template_part(
                'template-parts/pagination/pagination'
            );
            ?>

        <?php else : ?>

            <?php
            get_template_part(
                'template-parts/content/content-none'
            );
            ?>

        <?php endif; ?>

    </div>

<?php
get_footer();