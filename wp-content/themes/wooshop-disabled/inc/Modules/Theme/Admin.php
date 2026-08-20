<?php
/**
 * WooShop Admin Module
 *
 * Provides lightweight WooShop-specific administration hooks
 * and admin interface enhancements.
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
 * Class Admin
 *
 * Handles WooShop administration functionality.
 */
final class Admin extends Module {

    /**
     * Register administration functionality.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'admin_body_class',
            [$this, 'filter_admin_body_class']
        );

        add_action(
            'admin_head',
            [$this, 'add_admin_header']
        );
    }

    /**
     * Add WooShop class to the WordPress admin body.
     *
     * @param string $classes Existing admin body classes.
     *
     * @return string
     */
    public function filter_admin_body_class(
        string $classes
    ): string {

        $classes .= ' wooshop-admin';

        return trim($classes);
    }

    /**
     * Add WooShop admin header metadata.
     *
     * Provides a lightweight admin marker without adding
     * unnecessary markup or database queries.
     *
     * @return void
     */
    public function add_admin_header(): void {

        ?>
        <meta
                name="wooshop-admin"
                content="true"
        >
        <?php
    }
}