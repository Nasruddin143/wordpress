<?php
/**
 * Blog / Posts Index Layout
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<main
    id="primary"
    class="site-main ws-blog-main">

    <div class="ws-container">

        <?php
        get_template_part(
            'template-parts/blog/blog-header'
        );
        ?>

        <?php if ( have_posts() ) : ?>

            <div class="ws-posts-loop">

                <?php
                while ( have_posts() ) :

                    the_post();

                    get_template_part(
                        'template-parts/content/content'
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