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
     *
     * @return void
     */
    public function register(): void
    {

        /*
         * Theme Setup.
         */
        add_action(
            'after_setup_theme',
            [$this, 'register_menus']
        );

        /*
         * Header.
         */
        add_action(
            'wooshop_header',
            [$this, 'render']
        );

        /*
         * Filters.
         */
        add_filter(
            'body_class',
            [$this, 'body_classes']
        );

        /*
         * Header Sections.
         */
        add_action(
            'wooshop_header_branding',
            [$this, 'branding']
        );

        add_action(
            'wooshop_header_navigation',
            [$this, 'navigation']
        );

        add_action(
            'wooshop_header_categories',
            [$this, 'categories']
        );

        add_action(
            'wooshop_header_search',
            [$this, 'search']
        );

        add_action(
            'wooshop_header_actions',
            [$this, 'actions']
        );

    }

    /**
     * Register menus.
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
                //'footer' => __('Footer Menu', 'wooshop'),
            ]
        );

    }

    /**
     * Add body classes.
     *
     * @param array $classes Body classes.
     *
     * @return array
     */
    public function body_classes( array $classes ): array
    {
        $classes[] = 'ws-theme';

        if ( is_front_page() ) {
            $classes[] = 'ws-home';
            $classes[] = 'ws-front-page';
        }

        if ( is_home() && ! is_front_page() ) {
            $classes[] = 'ws-blog';
            $classes[] = 'ws-posts-page';
        }

        if ( is_archive() ) {
            $classes[] = 'ws-archive';
        }

        if ( is_search() ) {
            $classes[] = 'ws-search';
            $classes[] = 'ws-search-results-page';
        }

        if ( is_author() ) {
            $classes[] = 'ws-author-archive';
        }

        if ( is_category() ) {
            $classes[] = 'ws-category-archive';
        }

        if ( is_tag() ) {
            $classes[] = 'ws-tag-archive';
        }

        if ( is_tax() ) {
            $classes[] = 'ws-taxonomy-archive';
        }

        if ( is_404() ) {
            $classes[] = 'ws-404';
        }

        if ( is_page() ) {
            $classes[] = 'ws-page';
        }

        if ( class_exists( 'WooCommerce' ) ) {

            if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
                $classes[] = 'ws-woocommerce';
            }

            if ( function_exists( 'is_shop' ) && is_shop() ) {
                $classes[] = 'ws-shop';
            }

            if ( function_exists( 'is_product' ) && is_product() ) {
                $classes[] = 'ws-product';
            }

            if ( function_exists( 'is_product_category' ) && is_product_category() ) {
                $classes[] = 'ws-product-category';
            }

            if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
                $classes[] = 'ws-product-tag';
            }

            if ( function_exists( 'is_cart' ) && is_cart() ) {
                $classes[] = 'ws-cart';
            }

            if ( function_exists( 'is_checkout' ) && is_checkout() ) {
                $classes[] = 'ws-checkout';
            }

            if ( function_exists( 'is_account_page' ) && is_account_page() ) {
                $classes[] = 'ws-account';
            }
        }

        return $classes;
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

        $view->render('header/header');

    }

    /**
     * Render branding.
     *
     * @return void
     */
    public function branding(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('header/branding',
            [
                'show_tagline' => true,
            ]);

    }

    /**
     * Render navigation.
     *
     * @return void
     */
    public function navigation(): void
    {

        /** @var View $view */
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

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('header/categories');

    }

    /**
     * Render search.
     *
     * @return void
     */
    public function search(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('header/search',
            [
                'placeholder' => __(
                    'Search products...',
                    'wooshop'
                ),
                'show_button' => true,
            ]);

    }

    /**
     * Render actions.
     *
     * @return void
     */
    public function actions(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('header/actions');

    }

}