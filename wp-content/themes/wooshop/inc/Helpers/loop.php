<?php
/**
 * Loop Helpers
 *
 * Helpers for rendering WordPress content loops.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the current post content template.
 *
 * @param string $context Optional template context.
 *
 * @return void
 */
function wooshop_render_loop_item( string $context = '' ): void
{
    $post_type = get_post_type();

    if ( $context ) {
        $template = $context;
    } else {
        $template = $post_type;
    }

    get_template_part(
        'template-parts/content/content',
        $template
    );
}

/**
 * Render the standard WordPress loop.
 *
 * @param string $context Optional template context.
 *
 * @return void
 */
function wooshop_render_loop( string $context = '' ): void
{
    if ( ! have_posts() ) {
        get_template_part(
            'template-parts/content/content',
            'none'
        );

        return;
    }

    while ( have_posts() ) {
        the_post();

        wooshop_render_loop_item( $context );
    }
}


/**
 * Loop Helpers
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

/**
 * Render the standard WooShop post loop.
 *
 * @return void
 */
function wooshop_loop(): void
{
    if (have_posts()) {

        while (have_posts()) {

            the_post();

            get_template_part(
                'template-parts/content/content'
            );
        }

        return;
    }

    get_template_part(
        'template-parts/content/content',
        'none'
    );
}