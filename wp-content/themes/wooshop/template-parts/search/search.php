<?php
/**
 * Search Results Layout
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<main
    id="primary"
    class="site-main ws-search-page">

    <div class="ws-container">

        <?php
        get_template_part(
            'template-parts/search/search-header'
        );
        ?>

        <?php if ( have_posts() ) : ?>

            <div
                class="ws-search-results"
                aria-label="<?php esc_attr_e( 'Search results', 'wooshop' ); ?>">

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