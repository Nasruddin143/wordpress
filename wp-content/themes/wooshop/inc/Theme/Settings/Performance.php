<?php

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Performance
{

    public static function defaults(): array
    {

        return [

            'lazy_load' => true,

            'preload_fonts' => true,

            'defer_js' => true,

        ];

    }

}