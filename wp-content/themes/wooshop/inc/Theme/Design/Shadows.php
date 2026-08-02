<?php
/**
 * Shadow Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Shadows
{

    public static function tokens(): array
    {

        return [

            'shadow-sm' =>
                '0 .125rem .25rem rgba(0,0,0,.075)',

            'shadow' =>
                '0 .5rem 1rem rgba(0,0,0,.15)',

            'shadow-lg' =>
                '0 1rem 3rem rgba(0,0,0,.175)',

        ];
    }
}