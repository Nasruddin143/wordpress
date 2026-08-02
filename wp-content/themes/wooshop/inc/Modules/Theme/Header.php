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

        add_action(
            'after_setup_theme',
            [$this, 'register_menus']
        );

        add_action(
            'wooshop_header',
            [$this, 'render']
        );

        add_filter(
            'body_class',
            [$this, 'body_classes']
        );

        add_filter(
            'wp_nav_menu_args',
            [$this, 'menu_args']
        );

        add_filter(
            'nav_menu_css_class',
            [$this, 'menu_item_classes'],
            10,
            4
        );

        add_filter(
            'nav_menu_link_attributes',
            [$this, 'menu_link_attributes'],
            10,
            4
        );
    }

    /**
     * Register navigation menus.
     *
     * @return void
     */
    public function register_menus(): void
    {

        register_nav_menus(
            [
                'primary' => esc_html__('Primary Menu', 'wooshop'),
                'topbar' => esc_html__('Top Bar Menu', 'wooshop'),
                'catalog' => esc_html__('Category Menu', 'wooshop'),
            ]
        );
    }

    /**
     * Render site header.
     *
     * @return void
     */
    public function render(): void
    {

        /**
         * Before header.
         */
        do_action('wooshop_header_before');

        $view = $this->container->get(View::class);

        $view->render(
            'header/site-header'
        );

        /**
         * After header.
         */
        do_action('wooshop_header_after');
    }

    /**
     * Add body classes.
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes(array $classes): array
    {

        $classes[] = 'wooshop';

        if (has_custom_logo()) {
            $classes[] = 'has-site-logo';
        }

        if (is_front_page()) {
            $classes[] = 'is-homepage';
        }

        if (is_user_logged_in()) {
            $classes[] = 'logged-in-user';
        }

        return $classes;
    }

    /**
     * Default menu arguments.
     *
     * @param array $args Menu arguments.
     * @return array
     */
    public function menu_args(array $args): array
    {

        if ('primary' !== ($args['theme_location'] ?? '')) {
            return $args;
        }

        $args['container'] = false;
        $args['menu_class'] = 'navbar-nav ms-auto align-items-lg-center';
        $args['fallback_cb'] = false;
        $args['depth'] = 3;

        return $args;
    }

    /**
     * Menu item classes.
     *
     * @param array $classes Classes.
     * @param \WP_Post $item Menu item.
     * @param stdClass $args Arguments.
     * @param int $depth Depth.
     * @return array
     */
    public function menu_item_classes(
        array $classes,
              $item,
              $args,
        int   $depth
    ): array
    {

        if ('primary' !== ($args->theme_location ?? '')) {
            return $classes;
        }

        $classes[] = 'nav-item';

        if (in_array('menu-item-has-children', $classes, true)) {
            $classes[] = 'dropdown';
        }

        return array_unique($classes);
    }

    /**
     * Menu link attributes.
     *
     * @param array $atts Attributes.
     * @param \WP_Post $item Menu item.
     * @param stdClass $args Arguments.
     * @param int $depth Depth.
     * @return array
     */
    public function menu_link_attributes(
        array $atts,
              $item,
              $args,
        int   $depth
    ): array
    {

        if ('primary' !== ($args->theme_location ?? '')) {
            return $atts;
        }

        $atts['class'] = 'nav-link';

        if (in_array('menu-item-has-children', $item->classes, true)) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
        }

        return $atts;
    }
}