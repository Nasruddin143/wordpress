<?php
/**
 * WooShop Editor Module
 *
 * Handles classic editor and block-editor related theme
 * configuration that is separate from the main BlockEditor module.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WP_Editor;
use WooShop\Core\Config;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Editor
 *
 * Provides WooShop editor configuration and content-editor
 * improvements.
 */
final class Editor extends Module {

    /**
     * Register editor functionality.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'wp_editor_settings',
            [$this, 'filter_editor_settings'],
            10,
            2
        );

        add_filter(
            'tiny_mce_before_init',
            [$this, 'filter_tiny_mce_settings']
        );

        add_filter(
            'teeny_mce_before_init',
            [$this, 'filter_teeny_mce_settings']
        );

        add_filter(
            'mce_buttons',
            [$this, 'filter_mce_buttons']
        );

        add_filter(
            'mce_buttons_2',
            [$this, 'filter_mce_buttons_2']
        );
    }

    /**
     * Filter WordPress editor settings.
     *
     * Keeps the editor configuration lightweight and enables
     * useful HTML attributes without affecting frontend output.
     *
     * @param array<string, mixed> $settings Editor settings.
     * @param string               $editor_id Editor instance ID.
     *
     * @return array<string, mixed>
     */
    public function filter_editor_settings(
        array $settings,
        string $editor_id
    ): array {

        $settings['wpautop'] = true;

        $settings['media_buttons'] = true;

        $settings['teeny'] = false;

        $settings['quicktags'] = true;

        return $settings;
    }

    /**
     * Filter TinyMCE settings.
     *
     * @param array<string, mixed> $settings TinyMCE settings.
     *
     * @return array<string, mixed>
     */
    public function filter_tiny_mce_settings(
        array $settings
    ): array {

        $settings['toolbar1'] = implode(
            ',',
            array(
                'formatselect',
                'bold',
                'italic',
                'bullist',
                'numlist',
                'blockquote',
                'alignleft',
                'aligncenter',
                'alignright',
                'link',
                'unlink',
                'undo',
                'redo',
            )
        );

        $settings['toolbar2'] = implode(
            ',',
            array(
                'strikethrough',
                'hr',
                'removeformat',
                'charmap',
                'pastetext',
                'fullscreen',
            )
        );

        $settings['block_formats'] = implode(
            ';',
            array(
                'Paragraph=p',
                'Heading 2=h2',
                'Heading 3=h3',
                'Heading 4=h4',
                'Heading 5=h5',
                'Heading 6=h6',
                'Preformatted=pre',
            )
        );

        return $settings;
    }

    /**
     * Filter TeenyMCE settings.
     *
     * Keeps the compact editor configuration useful without
     * loading the full TinyMCE toolbar.
     *
     * @param array<string, mixed> $settings TeenyMCE settings.
     *
     * @return array<string, mixed>
     */
    public function filter_teeny_mce_settings(
        array $settings
    ): array {

        $settings['toolbar1'] = implode(
            ',',
            array(
                'bold',
                'italic',
                'bullist',
                'numlist',
                'link',
                'unlink',
                'undo',
                'redo',
            )
        );

        return $settings;
    }

    /**
     * Filter the first TinyMCE toolbar row.
     *
     * @param array<int, string> $buttons Existing buttons.
     *
     * @return array<int, string>
     */
    public function filter_mce_buttons(
        array $buttons
    ): array {

        return array_values(
            array_unique(
                $buttons
            )
        );
    }

    /**
     * Filter the second TinyMCE toolbar row.
     *
     * @param array<int, string> $buttons Existing buttons.
     *
     * @return array<int, string>
     */
    public function filter_mce_buttons_2(
        array $buttons
    ): array {

        return array_values(
            array_unique(
                $buttons
            )
        );
    }
}