<?php
/**
 * Theme Setup Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Theme setup.
 */
final class Setup extends Module
{
    /**
     * Theme configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);

        $config = require get_template_directory() . '/inc/Config/theme.php';

        $this->config = is_array($config) ? $config : array();
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('after_setup_theme', array($this, 'set_content_width'), 0);
    }

    /**
     * Setup theme features.
     *
     * @return void
     */
    public function setup(): void
    {
        $translation_path = $this->config['translation_path'] ?? 'languages';

        load_theme_textdomain('wooshop', get_template_directory() . '/languages');

        $this->register_supports();
        $this->register_menus();
    }

    /**
     * Register navigation menus.
     *
     * @return void
     */
    private function register_menus(): void
    {
        $menus = $this->config['menus'] ?? array();

        $locations = array();

        foreach ($menus as $location => $menu) {

            if (!is_array($menu)) {
                continue;
            }

            if (empty($menu['label'])) {
                continue;
            }

            $locations[$location] = $menu['label'];
        }

        if (!empty($locations)) {
            register_nav_menus($locations);
        }
    }

    /**
     * Register configured theme supports.
     *
     * @return void
     */
    private function register_supports(): void
    {

        $supports = $this->config['supports'] ?? array();

        foreach ($supports as $feature => $arguments) {

            /*
             * Boolean theme support.
             */
            if (true === $arguments) {
                add_theme_support((string)$feature);
                continue;
            }

            /*
             * Theme support with arguments.
             *
             * WordPress expects features such as html5, custom-logo,
             * custom-background, etc. to receive their configuration
             * as a single argument.
             */
            if (is_array($arguments)) {

                add_theme_support((string)$feature, $arguments);
                continue;

            }

            /*
             * Fallback for scalar arguments.
             */
            add_theme_support((string)$feature, $arguments);
        }
    }

    /**
     * Set global content width.
     *
     * @return void
     */
    public function set_content_width(): void
    {

        $content_width = $this->config['content_width'] ?? 640;

        $content_width = (int)apply_filters('wooshop_content_width', $content_width);

        $GLOBALS['content_width'] = $content_width;
    }
}