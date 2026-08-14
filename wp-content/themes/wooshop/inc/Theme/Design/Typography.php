<?php
/**
 * Typography Tokens
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Typography
{

    /**
     * Typography tokens.
     *
     * @return array
     */
    public static function tokens(): array
    {

        return [

            'font-body' =>
                'system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif',

            'font-heading' =>
                'inherit',

            'font-size' =>
                '16px',

            'line-height' =>
                '1.6',

            'font-weight' =>
                '400',

            'heading-weight' =>
                '700',

        ];
    }
}