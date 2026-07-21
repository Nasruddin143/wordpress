<?php

namespace KT\Features;

class ThemeHooks
{
    public function __construct()
    {
        add_filter('get_custom_logo', [$this, 'logo_class']);
    }

    public function logo_class($html)
    {
        return str_replace('custom-logo-link', 'navbar-brand', $html);
    }
}