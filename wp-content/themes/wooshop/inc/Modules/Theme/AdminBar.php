<?php
/**
 * WooShop Admin Bar Module
 *
 * Provides lightweight customization of the WordPress admin bar
 * for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class AdminBar
 *
 * Handles WooShop admin bar functionality.
 */
final class AdminBar extends Module {

    /**
     * Register admin bar functionality.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'admin_bar_menu',
            [$this, 'add_theme_menu'],
            100
        );

        add_action(
            'wp_before_admin_bar_render',
            [$this, 'remove_unnecessary_items']
        );
    }

    /**
     * Add the WooShop theme menu to the admin bar.
     *
     * @param \WP_Admin_Bar $admin_bar WordPress admin bar instance.
     *
     * @return void
     */
    public function add_theme_menu(
        \WP_Admin_Bar $admin_bar
    ): void {

        if (!current_user_can('edit_theme_options')) {
            return;
        }

        $admin_bar->add_node(
            array(
                'id'    => 'wooshop',
                'title' => esc_html__(
                    'WooShop',
                    'wooshop'
                ),
                'href'  => admin_url(
                    'themes.php'
                ),
                'meta'  => array(
                    'title' => esc_attr__(
                        'WooShop Theme',
                        'wooshop'
                    ),
                ),
            )
        );
    }

    /**
     * Remove unnecessary admin bar items.
     *
     * Keeps the admin bar lightweight while preserving
     * WordPress and WooCommerce functionality.
     *
     * @return void
     */
    public function remove_unnecessary_items(): void {

        if (!is_admin_bar_showing()) {
            return;
        }

        /*
         * Remove WordPress customization entry when the user
         * does not have permission to customize the theme.
         */
        if (!current_user_can('edit_theme_options')) {
            global $wp_admin_bar;

            if ($wp_admin_bar instanceof \WP_Admin_Bar) {
                $wp_admin_bar->remove_node('customize');
            }
        }
    }
}