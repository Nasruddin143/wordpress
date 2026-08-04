<?php

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

class Topbar extends Module
{

    public function register(): void
    {
        add_action(
            'wooshop_before_header',
            [$this, 'render']
        );
    }

    public function render(): void
    {
        $this->container
            ->get(\WooShop\Core\View::class)
            ->render('header/topbar');
    }
}