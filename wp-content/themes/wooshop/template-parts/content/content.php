<?php
/**
 * Default Content Template
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if (has_post_thumbnail()) : ?>

        <div class="entry-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
            </a>
        </div>

    <?php endif; ?>

    <?php wooshop_entry_header(); ?>

    <div class="entry-content">

        <?php

        if (is_singular()) {

            the_content();

            wp_link_pages(
                    [
                            'before' => '<div class="page-links">',
                            'after' => '</div>',
                    ]
            );

        } else {

            the_excerpt();

        }

        ?>

    </div>

</article>