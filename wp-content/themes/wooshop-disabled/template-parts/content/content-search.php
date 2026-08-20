<?php
/**
 * Search Result Content
 *
 * Displays an individual search result.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
    id="post-<?php the_ID(); ?>"
    <?php post_class( 'ws-search-result mb-4 pb-4 border-bottom' ); ?>
>

    <header class="entry-header">

        <?php
        the_title(
            '<h2 class="entry-title h4"><a class="text-decoration-none" href="' . esc_url( get_permalink() ) . '">',
            '</a></h2>'
        );
        ?>

    </header>

    <div class="entry-summary">

        <?php the_excerpt(); ?>

    </div>

</article>