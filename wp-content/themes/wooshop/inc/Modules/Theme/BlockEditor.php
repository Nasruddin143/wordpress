<?php
/**
 * WooShop Block Editor Module
 *
 * Configures the WordPress block editor integration for the
 * WooShop theme while keeping editor assets separate from
 * frontend assets.
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
 * Class BlockEditor
 *
 * Handles WooShop block editor functionality.
 */
final class BlockEditor extends Module {

    /**
     * Register block editor functionality.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [$this, 'setup_editor_support'],
            20
        );

        add_filter(
            'block_editor_settings_all',
            [$this, 'filter_editor_settings'],
            10,
            2
        );

        add_filter(
            'block_categories_all',
            [$this, 'register_block_category'],
            10,
            2
        );
    }

    /**
     * Register theme block editor support.
     *
     * Keeps editor support aligned with the WooShop theme.
     *
     * @return void
     */
    public function setup_editor_support(): void {

        add_theme_support(
            'editor-styles'
        );

        add_theme_support(
            'align-wide'
        );

        add_theme_support(
            'responsive-embeds'
        );

        add_theme_support(
            'wp-block-styles'
        );
    }

    /**
     * Filter block editor settings.
     *
     * Prevents unnecessary editor-side features from adding
     * frontend overhead while keeping the editor functional.
     *
     * @param array<string, mixed> $settings Editor settings.
     * @param \WP_Block_Editor_Context $context Editor context.
     *
     * @return array<string, mixed>
     */
    public function filter_editor_settings(
        array $settings,
        \WP_Block_Editor_Context $context
    ): array {

        $settings['styles'][] = array(
            'css' => ':root{--wooshop-editor:1;}',
        );

        return $settings;
    }

    /**
     * Register the WooShop block category.
     *
     * @param array<int, array<string, string>> $categories
     *        Existing block categories.
     * @param \WP_Block_Editor_Context $context
     *        Block editor context.
     *
     * @return array<int, array<string, string>>
     */
    public function register_block_category(
        array $categories,
        \WP_Block_Editor_Context $context
    ): array {

        $categories[] = array(
            'slug'  => 'wooshop',
            'title' => esc_html__(
                'WooShop',
                'wooshop'
            ),
            'icon'  => 'cart',
        );

        return $categories;
    }
}