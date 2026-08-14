<?php
/**
 * Entry Header Component
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header class="entry-header">

    <?php
    if ( is_singular() ) {

        the_title(
            '<h1 class="entry-title">',
            '</h1>'
        );

    } else {

        the_title(
            sprintf(
                '<h2 class="entry-title"><a href="%s" rel="bookmark">',
                esc_url( get_permalink() )
            ),
            '</a></h2>'
        );

    }
    ?>

    <?php get_template_part( 'template-parts/components/post-meta' ); ?>

</header>