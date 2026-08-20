<?php
/**
 * WooShop Template Function Compatibility.
 *
 * Provides procedural wrappers for classic WordPress template files.
 *
 * @package WooShop
 */

use WooShop\Modules\Theme\TemplateTags;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_post_thumbnail' ) ) {

    /**
     * Display post thumbnail.
     *
     * @return void
     */
    function wooshop_post_thumbnail(): void {
        TemplateTags::post_thumbnail();
    }
}

if ( ! function_exists( 'wooshop_posted_on' ) ) {

    /**
     * Display post date.
     *
     * @return void
     */
    function wooshop_posted_on(): void {
        TemplateTags::posted_on();
    }
}

if ( ! function_exists( 'wooshop_posted_by' ) ) {

    /**
     * Display post author.
     *
     * @return void
     */
    function wooshop_posted_by(): void {
        TemplateTags::posted_by();
    }
}

if ( ! function_exists( 'wooshop_entry_footer' ) ) {

    /**
     * Display entry footer.
     *
     * @return void
     */
    function wooshop_entry_footer(): void {
        TemplateTags::entry_footer();
    }
}