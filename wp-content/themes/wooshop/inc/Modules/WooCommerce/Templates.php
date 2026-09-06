<?php
/**
 * WooCommerce Templates Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * WooCommerce templates module.
 */
final class Templates extends Module
{
    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        if (!$this->is_available()) {
            return;
        }

        add_filter('woocommerce_locate_template', [$this, 'locate_template'], 10, 4);
    }

    /**
     * Check WooCommerce availability.
     *
     * @return bool
     */
    private function is_available(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Locate a WooShop WooCommerce template override.
     *
     * @param string $template Located template.
     * @param string $template_name Template name.
     * @param string $template_path WooCommerce template path.
     * @param string $default_path WooCommerce default template path.
     *
     * @return string
     */
    public function locate_template(string $template, string $template_name, string $template_path, string $default_path): string
    {
        unset($template_path, $default_path);

        $theme_template = get_template_directory() . '/template-parts/woocommerce/' . $template_name;

        if (file_exists($theme_template)) {
            return $theme_template;
        }

        return $template;
    }
}