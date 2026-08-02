<?php
/**
 * Radius Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Radius
{

    public static function tokens(): array
    {

        return [

            'radius-sm' => '.25rem',

            'radius' => '.5rem',

            'radius-lg' => '.75rem',

            'radius-xl' => '1rem',

            'radius-pill' => '999px',

        ];
    }
}