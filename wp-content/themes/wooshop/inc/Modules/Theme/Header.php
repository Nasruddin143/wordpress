<?php
/**
 * Header Module
 *
 * Handles the frontend header.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\View;

defined('ABSPATH') || exit;

class Header extends Module
{

    /**
     * Register module.
     */
    public function register(): void
    {

        add_action('after_setup_theme', [$this, 'register_menus']);

        add_action('wooshop_header', [$this, 'render']);

        add_filter('body_class', [$this, 'body_classes']);

        add_action('wooshop_header_branding', [$this, 'branding']);

        add_action('wooshop_header_navigation', [$this, 'navigation']);

        add_action('wooshop_header_categories', [$this, 'categories']);

        add_action('wooshop_header_search', [$this, 'search']);
    }

    /**
     * Register header menus.
     *
     * @return void
     */
    public function register_menus(): void
    {

        register_nav_menus(
            [
                'primary' => __('Primary Menu', 'wooshop'),
                'topbar' => __('Top Bar Menu', 'wooshop'),
                'categories' => __('Category Menu', 'wooshop'),
            ]
        );
    }

    /**
     * Render header.
     *
     * @return void
     */
    public function render(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('header/site-header');
    }

    /**
     * Add body classes.
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes(array $classes): array
    {

        $classes[] = 'ws-theme';

        if (is_front_page()) {
            $classes[] = 'ws-home';
        }

        return $classes;
    }

    /**
     * Render branding.
     *
     * @return void
     */
    public function branding(): void
    {

        $view = $this->container->get(View::class);

        $view->render('header/branding');
    }

    /**
     * Render navigation.
     *
     * @return void
     */
    public function navigation(): void
    {

        $view = $this->container->get(View::class);

        $view->render('header/navigation');
    }

    /**
     * Render categories.
     *
     * @return void
     */
    public function categories(): void
    {

        $view = $this->container->get(View::class);

        $view->render('header/categories');
    }

    /**
     * Render search.
     */
    public function search(): void {

        $view = $this->container->get( View::class );

        $view->render( 'header/search' );
    }
}