<?php
/**
 * Formatting Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_trim_words' ) ) {

    function wooshop_trim_words(
        string $text,
        int $length = 20
    ): string {

        return wp_trim_words(
            $text,
            $length
        );
    }
}