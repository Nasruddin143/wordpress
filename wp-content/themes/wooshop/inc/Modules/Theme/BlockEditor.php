<?php
/**
 * Block Editor Module.
 *
 * Handles WordPress Block Editor configuration for WooShop.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress Block Editor functionality.
 */
class BlockEditor extends Module {

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
            'enqueue_block_editor_assets',
            [ $this, 'enqueue_editor_assets' ]
        );

        add_filter(
            'block_editor_settings_all',
            [ $this, 'editor_settings' ]
        );
    }

    /**
     * Handle Block Editor assets.
     *
     * Asset loading remains delegated to the WooShop asset system.
     *
     * @return void
     */
    public function enqueue_editor_assets(): void {

        /**
         * Block Editor-specific assets should be registered
         * through AssetsManager when required.
         */
    }

    /**
     * Configure Block Editor settings.
     *
     * @param array $settings Block Editor settings.
     * @return array
     */
    public function editor_settings( array $settings ): array {

        /**
         * Keep WordPress defaults intact.
         *
         * Theme-specific editor settings can be added here
         * when the WooShop design system requires them.
         */

        return $settings;
    }
}