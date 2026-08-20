<?php
/**
 * WooShop Template Module
 *
 * Provides centralized template-related configuration and
 * helper methods for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Template
{
    /**
     * Register the template module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'template_include',
            [$this, 'filter_template_include']
        );

        add_filter(
            'body_class',
            [$this, 'filter_body_class']
        );
    }

    /**
     * Filter the selected template.
     *
     * WooShop uses WordPress template hierarchy directly, so
     * templates are not replaced or redirected by this module.
     *
     * @param string $template Selected template path.
     *
     * @return string
     */
    public function filter_template_include(
        string $template
    ): string {
        return $template;
    }

    /**
     * Add WooShop template context classes to the body.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function filter_body_class(array $classes): array
    {
        if (is_front_page()) {
            $classes[] = 'ws-front-page';
        }

        if (is_home()) {
            $classes[] = 'ws-blog-page';
        }

        if (is_page()) {
            $classes[] = 'ws-page';
        }

        if (is_single()) {
            $classes[] = 'ws-single';
        }

        if (is_archive()) {
            $classes[] = 'ws-archive';
        }

        if (is_search()) {
            $classes[] = 'ws-search';
        }

        if (is_404()) {
            $classes[] = 'ws-404';
        }

        return array_values(
            array_unique($classes)
        );
    }
}