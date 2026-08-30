<?php
/**
 * WooShop Theme Setup Module
 *
 * Registers WordPress theme supports, navigation menus, HTML5
 * support, editor styles, and related theme configuration — all
 * driven by /inc/Config/theme.php via the service container.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Setup
 */
final class Setup extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);
    }

    /**
     * Configure the WordPress theme from theme.php config.
     *
     * @return void
     */
    public function setup(): void
    {
        // Bug 3 & 4 fix: pull config from the container instead of hardcoding.
        // Previously all values were hardcoded and conflicted with theme.php.
        /** @var Config $config */
        $config = $this->container->get('config')->get('theme');

        /*
         * ---------------------------------------------------------
         * Translation
         * ---------------------------------------------------------
         */

        $text_domain = $config['text_domain'] ?? 'wooshop';
        $translation_path = $config['translation_path'] ?? 'languages';

        load_theme_textdomain($text_domain, get_template_directory() . '/' . $translation_path);

        /*
         * ---------------------------------------------------------
         * Theme supports
         * ---------------------------------------------------------
         */

        $supports = $config['supports'] ?? [];

        foreach ($supports as $feature => $args) {
            if ($args === true) {
                add_theme_support($feature);
            } elseif (is_array($args)) {
                add_theme_support($feature, $args);
            }
        }

        /*
         * ---------------------------------------------------------
         * Navigation menus
         * ---------------------------------------------------------
         */

        $menus = $config['menus'] ?? [];

        if (!empty($menus)) {
            $nav_menus = [];

            foreach ($menus as $location => $menu) {
                $nav_menus[$location] = $menu['label'] ?? ucfirst($location) . ' Menu';
            }

            register_nav_menus($nav_menus);
        }

        /*
         * ---------------------------------------------------------
         * Content width
         * ---------------------------------------------------------
         */

        $content_width = $config['content_width'] ?? 640;

        if (!isset($GLOBALS['content_width'])) {
            $GLOBALS['content_width'] = (int)$content_width;
        }

        /*
         * ---------------------------------------------------------
         * Widget areas
         * ---------------------------------------------------------
         */

        $widget_areas = $config['widget_areas'] ?? [];

        if (!empty($widget_areas)) {
            add_action('widgets_init', function () use ($widget_areas): void {
                foreach ($widget_areas as $id => $area) {
                    register_sidebar([
                        'id' => $id,
                        'name' => $area['name'] ?? $id,
                        'description' => $area['description'] ?? '',
                    ]);
                }
            });
        }

        /*
         * ---------------------------------------------------------
         * Custom header
         * ---------------------------------------------------------
         */

        if (!empty($config['custom_header'])) {
            add_theme_support('custom-header', $config['custom_header']);
        }

        /*
         * ---------------------------------------------------------
         * Custom background
         * ---------------------------------------------------------
         */

        if (!empty($config['custom_background'])) {
            add_theme_support('custom-background', $config['custom_background']);
        }

        /*
         * ---------------------------------------------------------
         * Editor styles
         * ---------------------------------------------------------
         */

        add_theme_support('editor-styles');
    }
}