<?php
/**
 * Custom Background Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Custom background module.
 */
final class Background extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        add_action(
            'after_setup_theme',
            array( $this, 'register_background' )
        );
    }

    /**
     * Register custom background.
     *
     * @return void
     */
    public function register_background(): void {

        add_theme_support(
            'custom-background',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        );
    }
}