<?php

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Layout
{

    public static function defaults(): array
    {

        return [

            'container_width' => '1320px',

            'sidebar_width' => '320px',

            'header_height' => '80px',

        ];

    }

}