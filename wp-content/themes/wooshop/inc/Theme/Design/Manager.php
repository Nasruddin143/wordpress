<?php
/**
 * Design Manager
 *
 * @package WooShop
 */

namespace WooShop\Theme\Design;

defined('ABSPATH') || exit;

class Manager
{

    /**
     * Return all design tokens.
     *
     * @return array
     */
    public static function tokens(): array
    {

        return array_merge(

            Colors::tokens(),

            Typography::tokens(),

            Layout::tokens(),

            Buttons::tokens(),

            Radius::tokens(),

            Shadows::tokens(),

            Spacing::tokens()

        );
    }
}