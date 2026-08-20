<?php
/**
 * WooShop Navigation Module
 *
 * Registers WooShop navigation menus and provides centralized
 * navigation-related configuration for the theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use stdClass;
use WP_Post;

defined('ABSPATH') || exit;

final class Navigation
{
    /**
     * Registered navigation locations.
     *
     * @var array<string, string>
     */
    private array $locations = [
        'primary' => 'Primary Menu',
        'secondary' => 'Secondary Menu',
        'footer' => 'Footer Menu',
        'mobile' => 'Mobile Menu',
    ];

    /**
     * Register the navigation module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'register_navigation_menus']
        );

        add_filter(
            'nav_menu_css_class',
            [$this, 'filter_menu_item_classes'],
            10,
            4
        );

        add_filter(
            'nav_menu_link_attributes',
            [$this, 'filter_menu_link_attributes'],
            10,
            4
        );
    }

    /**
     * Register WooShop navigation menu locations.
     *
     * @return void
     */
    public function register_navigation_menus(): void
    {
        register_nav_menus($this->locations);
    }

    /**
     * Add WooShop classes to navigation menu items.
     *
     * @param array<int, string> $classes Menu item classes.
     * @param WP_Post            $item    Menu item object.
     * @param stdClass            $args    Menu arguments.
     * @param int                $depth   Menu depth.
     *
     * @return array<int, string>
     */
    public function filter_menu_item_classes(
        array    $classes,
        WP_Post  $item,
        stdClass $args,
        int      $depth
    ): array {
        $classes[] = 'ws-menu-item';

        if ($depth > 0) {
            $classes[] = 'ws-menu-item-depth-' . $depth;
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Add WooShop attributes to navigation links.
     *
     * @param array<string, string> $atts  Link attributes.
     * @param WP_Post               $item  Menu item object.
     * @param stdClass              $args  Menu arguments.
     * @param int                   $depth Menu depth.
     *
     * @return array<string, string>
     */
    public function filter_menu_link_attributes(
        array    $atts,
        WP_Post  $item,
        stdClass $args,
        int      $depth
    ): array {
        $classes = [
            'ws-menu-link',
        ];

        if ($depth > 0) {
            $classes[] = 'ws-menu-link-depth-' . $depth;
        }

        $existing_class = $atts['class'] ?? '';

        if ($existing_class !== '') {
            $classes[] = $existing_class;
        }

        $atts['class'] = implode(
            ' ',
            array_values(
                array_unique(
                    array_filter($classes)
                )
            )
        );

        return $atts;
    }
}