<?php
/**
 * WordPress Widgets.
 *
 * Handles theme-specific widget functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress widget functionality.
 */
class Widgets extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'widget_text',
            [ $this, 'allow_html_in_text_widget' ]
        );

        add_filter(
            'dynamic_sidebar_params',
            [ $this, 'add_widget_classes' ]
        );
    }

    /**
     * Allow WordPress to process shortcodes inside text widgets.
     *
     * @param string $content Widget content.
     * @return string
     */
    public function allow_html_in_text_widget( string $content ): string {

        return do_shortcode( $content );
    }

    /**
     * Add Bootstrap-compatible classes to widgets.
     *
     * @param array $params Sidebar widget parameters.
     * @return array
     */
    public function add_widget_classes( array $params ): array {

        if ( empty( $params[0]['before_widget'] ) ) {
            return $params;
        }

        $params[0]['before_widget'] = str_replace(
            'class="',
            'class="widget-item ',
            $params[0]['before_widget']
        );

        return $params;
    }
}