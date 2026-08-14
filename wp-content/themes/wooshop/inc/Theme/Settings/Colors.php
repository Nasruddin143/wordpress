<?php

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Colors
{

    /**
     * Default settings.
     */
    public static function defaults(): array
    {

        return [

            'primary_color' => '#0d6efd',

            'secondary_color' => '#6c757d',

            'success_color' => '#198754',

            'danger_color' => '#dc3545',

            'warning_color' => '#ffc107',

            'body_color' => '#495057',

            'heading_color' => '#212529',

            'background_color' => '#ffffff',

            'border_color' => '#dee2e6',

        ];

    }

}