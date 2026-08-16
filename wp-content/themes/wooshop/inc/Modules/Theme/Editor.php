<?php
/**
 * WordPress Editor.
 *
 * Handles editor-specific functionality for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress editor functionality.
 */
class Editor extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'admin_init',
            [ $this, 'editor_setup' ]
        );

//        add_filter(
//            'block_editor_settings_all',
//            [ $this, 'editor_settings' ]
//        );
    }

    /**
     * Configure WordPress editor support.
     *
     * @return void
     */
    public function editor_setup(): void {

        add_editor_style(
            'assets/build/css/editor.min.css'
        );
    }

    /**
     * Configure block editor settings.
     *
     * @param array $settings Editor settings.
     * @return array
     */
//    public function editor_settings( array $settings ): array {
//
//        $settings['styles'][] = [
//            'css' => '
//				.editor-styles-wrapper {
//					max-width: 100%;
//				}
//			',
//        ];
//
//        return $settings;
//    }
}