<?php

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Defaults
{

    public static function all(): array
    {

        return array_merge(

            Colors::defaults(),

            Typography::defaults(),

            Layout::defaults(),

            Buttons::defaults(),

            Social::defaults(),

            Performance::defaults()

        );

    }

}