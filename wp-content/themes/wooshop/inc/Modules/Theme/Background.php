<?php
/**
 * WooShop Background Module
 *
 * Adds WordPress custom-background support driven by
 * theme.php config and outputs a body class when an
 * image is active.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Background
 */
final class Background extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'add_support']);
        add_filter('body_class',        [$this, 'body_class']);
    }

    /**
     * Register custom-background theme support from theme.php.
     *
     * theme.php structure:
     *
     *   'custom_background' => [
     *       'default-color' => 'ffffff',
     *       'default-image' => '',
     *   ],
     *
     * @return void
     */
    public function add_support(): void
    {
        $config = $this->container->get('config')->get('theme');
        $args   = $config['custom_background'] ?? [];

        $defaults = [
            'default-color' => 'ffffff',
            'default-image' => '',
        ];

        add_theme_support('custom-background', array_merge($defaults, $args));
    }

    /**
     * Add a body class when a custom background image is set.
     *
     * @param string[] $classes Existing body classes.
     *
     * @return string[]
     */
    public function body_class(array $classes): array
    {
        if (get_background_image()) {
            $classes[] = 'has-background-image';
        }

        return $classes;
    }
}