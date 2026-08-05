<?php
/**
 * Icon Helper Functions
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

use WooShop\Core\Application;
use WooShop\Core\Icons\Icon;

if (!function_exists('wooshop_icon')) {

    /**
     * Render an SVG icon.
     *
     * @param string $name Icon name.
     * @param array $args Icon arguments.
     *
     * @return string
     */
    function wooshop_icon( string $name, array $args = [] ): string {

        return Application::container()
            ->get( Icon::class )
            ->render( $name, $args );
    }
}