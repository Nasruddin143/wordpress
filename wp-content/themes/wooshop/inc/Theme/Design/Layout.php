<?php
/**
 * Layout Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined( 'ABSPATH' ) || exit;

class Layout {

    /**
     * Layout tokens.
     *
     * @return array
     */
    public static function tokens(): array {

        return [

            'container'       => '1320px',

            'container-small' => '960px',

            'container-wide'  => '1440px',

            'header-height'   => '80px',

            'sidebar-width'   => '320px',

        ];
    }
}