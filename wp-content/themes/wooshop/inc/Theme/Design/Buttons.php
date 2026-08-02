<?php
/**
 * Button Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Buttons
{

    /**
     * Button tokens.
     *
     * @return array
     */
    public static function tokens(): array
    {

        return [

            'button-padding-y' => '.75rem',

            'button-padding-x' => '1.25rem',

            'button-radius' => '.5rem',

            'button-shadow' => '0 .25rem .75rem rgba(0,0,0,.12)',

        ];
    }
}