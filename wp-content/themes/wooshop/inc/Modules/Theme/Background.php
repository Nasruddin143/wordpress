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
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );

        $config = require get_template_directory()
            . '/inc/Config/theme.php';

        $this->config = is_array( $config ) ? $config : array();
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

        $args = $this->config['custom_background']
            ?? array();

        $args = apply_filters(
            'wooshop_custom_background_args',
            $args
        );

        add_theme_support(
            'custom-background',
            $args
        );
    }
}