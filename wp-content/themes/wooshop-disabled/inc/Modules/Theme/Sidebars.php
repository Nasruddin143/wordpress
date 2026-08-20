<?php
/**
 * WooShop Sidebars Module
 *
 * Registers the widget areas used by the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Sidebars
{
    /**
     * Register the sidebars module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'widgets_init',
            [$this, 'register_sidebars']
        );
    }

    /**
     * Register WooShop widget areas.
     *
     * @return void
     */
    public function register_sidebars(): void
    {
        $this->register_sidebar(
            'sidebar-primary',
            __('Primary Sidebar', 'wooshop'),
            __('Main sidebar widget area.', 'wooshop')
        );

        $this->register_sidebar(
            'sidebar-shop',
            __('Shop Sidebar', 'wooshop'),
            __('WooCommerce shop and product archive sidebar.', 'wooshop')
        );

        $this->register_sidebar(
            'sidebar-footer-1',
            __('Footer Column 1', 'wooshop'),
            __('First footer widget area.', 'wooshop')
        );

        $this->register_sidebar(
            'sidebar-footer-2',
            __('Footer Column 2', 'wooshop'),
            __('Second footer widget area.', 'wooshop')
        );

        $this->register_sidebar(
            'sidebar-footer-3',
            __('Footer Column 3', 'wooshop'),
            __('Third footer widget area.', 'wooshop')
        );

        $this->register_sidebar(
            'sidebar-footer-4',
            __('Footer Column 4', 'wooshop'),
            __('Fourth footer widget area.', 'wooshop')
        );
    }

    /**
     * Register a single WooShop sidebar.
     *
     * @param string $id          Sidebar identifier.
     * @param string $name        Sidebar display name.
     * @param string $description Sidebar description.
     *
     * @return void
     */
    private function register_sidebar(
        string $id,
        string $name,
        string $description
    ): void {
        register_sidebar(
            [
                'id'            => $id,
                'name'          => $name,
                'description'   => $description,
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
    }
}