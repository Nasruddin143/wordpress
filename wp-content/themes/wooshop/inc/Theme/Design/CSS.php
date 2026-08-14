<?php
/**
 * CSS Variable Generator
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class CSS
{

    /**
     * Print CSS variables.
     *
     * @return void
     */
    public static function output(): void
    {

        $tokens = Manager::tokens();

        echo '<style id="wooshop-design-tokens">';
        echo ':root{';

        foreach ($tokens as $name => $value) {
            printf(
                '--ws-%1$s:%2$s;',
                esc_attr($name),
                esc_attr($value)
            );
        }

        echo '}';
        echo '</style>';
    }
}