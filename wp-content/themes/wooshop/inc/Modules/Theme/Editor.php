<?php
/**
 * WooShop Editor Module
 *
 * Enqueues block-editor styles, registers custom block
 * patterns, and adds a custom block category.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WP_Block_Editor_Context;

defined('ABSPATH') || exit;

/**
 * Class Editor
 */
final class Editor extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'add_editor_styles']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        add_filter('block_categories_all', [$this, 'register_block_category'], 10, 2);
        add_action('init', [$this, 'register_block_patterns']);
    }

    /**
     * Register editor stylesheets so the block editor
     * visually matches the front-end theme.
     *
     * Files must exist at the declared paths, otherwise
     * WordPress silently skips the missing stylesheet.
     *
     * @return void
     */
    public function add_editor_styles(): void
    {
        add_theme_support('editor-styles');

        // Global design tokens shared between editor and front-end.
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Enqueue additional JS/CSS inside the block editor iframe.
     *
     * @return void
     */
    public function enqueue_editor_assets(): void
    {
        $theme_dir = get_template_directory();
        $theme_uri = get_template_directory_uri();

        $css_path = '/assets/css/editor-blocks.css';
        $js_path = '/assets/js/editor-blocks.js';

        if (file_exists($theme_dir . $css_path)) {
            wp_enqueue_style('wooshop-editor-blocks', $theme_uri . $css_path, [], (string)filemtime($theme_dir . $css_path));
        }

        if (file_exists($theme_dir . $js_path)) {
            wp_enqueue_script('wooshop-editor-blocks', $theme_uri . $js_path, ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'], (string)filemtime($theme_dir . $js_path), true);
        }
    }

    /**
     * Add a custom "WooShop" block category at the top of the inserter.
     *
     * @param array<int, array<string, mixed>> $categories Existing categories.
     * @param WP_Block_Editor_Context $context Editor context.
     *
     * @return array<int, array<string, mixed>>
     */
    public function register_block_category(array $categories, WP_Block_Editor_Context $context): array
    {
        return array_merge(
            [
                [
                    'slug' => 'wooshop',
                    'title' => __('WooShop', 'wooshop'),
                    'icon' => 'store',
                ],
            ],
            $categories
        );
    }

    /**
     * Register reusable block patterns.
     *
     * Pattern files live in inc/Patterns/ and are plain
     * PHP files that echo HTML. Add entries here to expose
     * them in the inserter under the WooShop category.
     *
     * @return void
     */
    public function register_block_patterns(): void
    {
        if (!function_exists('register_block_pattern')) {
            return;
        }

        $patterns_dir = get_template_directory() . '/inc/Patterns';

        if (!is_readable($patterns_dir)) {
            return;
        }

        $patterns = [
            'wooshop/hero-banner' => [
                'title' => __('Hero Banner', 'wooshop'),
                'description' => __('Full-width hero with heading and CTA.', 'wooshop'),
                'categories' => ['wooshop'],
                'file' => $patterns_dir . '/hero-banner.php',
            ],
            'wooshop/product-grid' => [
                'title' => __('Product Grid', 'wooshop'),
                'description' => __('3-column product grid block.', 'wooshop'),
                'categories' => ['wooshop', 'woocommerce'],
                'file' => $patterns_dir . '/product-grid.php',
            ],
        ];

        foreach ($patterns as $name => $pattern) {
            $file = $pattern['file'];

            if (!is_readable($file)) {
                continue;
            }

            ob_start();
            require $file;
            $content = ob_get_clean();

            register_block_pattern($name, [
                'title' => $pattern['title'],
                'description' => $pattern['description'],
                'categories' => $pattern['categories'],
                'content' => $content,
            ]);
        }
    }
}