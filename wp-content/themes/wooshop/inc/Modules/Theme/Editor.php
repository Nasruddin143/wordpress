<?php
/**
 * WooShop Editor Module
 *
 * Configures WordPress editor styles and editor-specific
 * functionality for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Editor
{
    /**
     * Register the editor module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'register_editor_support']
        );

        add_action(
            'admin_init',
            [$this, 'enqueue_editor_styles']
        );
    }

    /**
     * Register WordPress editor support.
     *
     * @return void
     */
    public function register_editor_support(): void
    {
        add_theme_support('editor-styles');

        add_editor_style(
            'assets/build/css/editor.min.css'
        );
    }

    /**
     * Enqueue editor-specific styles.
     *
     * The editor stylesheet is loaded only inside the
     * WordPress editor context.
     *
     * @return void
     */
    public function enqueue_editor_styles(): void
    {
        if (!is_admin()) {
            return;
        }

        if (!function_exists('get_current_screen')) {
            return;
        }

        $screen = get_current_screen();

        if (!$screen) {
            return;
        }

        if (
            !in_array(
                $screen->base,
                [
                    'post',
                    'post-new',
                ],
                true
            )
        ) {
            return;
        }

        $style_path = get_theme_file_path(
            '/assets/build/css/editor.min.css'
        );

        $style_uri = get_theme_file_uri(
            '/assets/build/css/editor.min.css'
        );

        if (!file_exists($style_path)) {
            return;
        }

        wp_enqueue_style(
            'wooshop-editor',
            $style_uri,
            [],
            (string) filemtime($style_path)
        );
    }
}