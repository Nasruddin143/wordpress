<?php
/**
 * Theme Localization Module.
 *
 * Handles translation and localization functionality for WooShop.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop localization.
 */
class Localization extends Module {

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        parent::__construct( $container );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'load_textdomain' ]
        );
    }

    /**
     * Load the WooShop translation files.
     *
     * WordPress automatically handles translations for modern themes,
     * but explicitly registering the theme text domain keeps the
     * theme architecture predictable.
     *
     * @return void
     */
    public function load_textdomain(): void {

        load_theme_textdomain(
            'wooshop',
            get_template_directory() . '/languages'
        );
    }
}