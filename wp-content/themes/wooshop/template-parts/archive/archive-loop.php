<?php
/**
 * Archive Loop
 *
 * Displays posts within archive contexts.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-archive-loop">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : ?>

            <?php the_post(); ?>

            <?php
            get_template_part(
                'template-parts/content/content'
            );
            ?>

        <?php endwhile; ?>

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