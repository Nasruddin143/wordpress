<?php

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Typography
{

    public static function defaults(): array
    {

        return [

            'body_font_family' =>

                'system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif',

            'heading_font_family' =>

                'inherit',

            'body_font_size' =>

                '16px',

            'line_height' =>

                '1.6',

        ];

    }

}