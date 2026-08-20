<?php
/**
 * WooShop Post Formats Module
 *
 * Registers supported WordPress post formats for the WooShop
 * theme and provides post-format body classes.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class PostFormats
{
    /**
     * Supported post formats.
     *
     * @var array<int, string>
     */
    private array $formats = [
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ];

    /**
     * Register the post formats module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'register_post_formats']
        );

        add_filter(
            'post_class',
            [$this, 'filter_post_classes'],
            10,
            3
        );
    }

    /**
     * Register supported WordPress post formats.
     *
     * @return void
     */
    public function register_post_formats(): void
    {
        add_theme_support(
            'post-formats',
            $this->formats
        );
    }

    /**
     * Add WooShop post-format classes.
     *
     * @param array<int, string> $classes Existing post classes.
     * @param array<int, string> $class   Additional classes.
     * @param int|WP_Post|null   $post    Post object or post ID.
     *
     * @return array<int, string>
     */
    public function filter_post_classes(
        array $classes,
        array $class = [],
        mixed $post = null
    ): array {
        $format = get_post_format($post);

        if ($format === false) {
            $classes[] = 'ws-post-format-standard';

            return array_values(
                array_unique($classes)
            );
        }

        $classes[] = 'ws-post-format-' . sanitize_html_class(
                $format
            );

        return array_values(
            array_unique($classes)
        );
    }
}