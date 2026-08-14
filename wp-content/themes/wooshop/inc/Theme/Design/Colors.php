<?php
/**
 * Color Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Colors
{

    /**
     * Return color tokens.
     *
     * @return array
     */
    public static function tokens(): array
    {

        return [

            'primary' => '#0d6efd',
            'primary-hover' => '#0b5ed7',

            'secondary' => '#6c757d',
            'secondary-hover' => '#5c636a',

            'success' => '#198754',
            'danger' => '#dc3545',
            'warning' => '#ffc107',
            'info' => '#0dcaf0',

            'dark' => '#212529',
            'light' => '#f8f9fa',

            'body' => '#495057',
            'heading' => '#212529',

            'background' => '#ffffff',

            'border' => '#dee2e6',

        ];
    }
}