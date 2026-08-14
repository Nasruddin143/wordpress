<?php
/**
 * General Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_get_svg_icon' ) ) {

    /**
     * Return SVG icon placeholder.
     *
     * @param string $icon Icon name.
     *
     * @return string
     */
    function wooshop_get_svg_icon( string $icon ): string {

        return apply_filters(
            'wooshop_svg_icon',
            '',
            $icon
        );
    }
}