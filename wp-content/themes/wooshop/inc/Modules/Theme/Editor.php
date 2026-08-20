<?php
/**
 * Editor Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Editor module.
 */
final class Editor extends Module {

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
            'admin_init',
            array( $this, 'register_editor_styles' )
        );

        add_action(
            'after_setup_theme',
            array( $this, 'editor_support' )
        );
    }

    /**
     * Register editor support.
     *
     * @return void
     */
    public function editor_support(): void {
        add_theme_support( 'editor-styles' );
    }

    /**
     * Register editor styles.
     *
     * @return void
     */
    public function register_editor_styles(): void {

        $stylesheet = get_template_directory() . '/assets/build/css/editor.min.css';

        if ( file_exists( $stylesheet ) ) {
            add_editor_style(
                'assets/build/css/editor.min.css'
            );
        }
    }
}