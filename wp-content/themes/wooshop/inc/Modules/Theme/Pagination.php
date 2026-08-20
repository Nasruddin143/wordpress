<?php
/**
 * WooShop Pagination Module
 *
 * Provides centralized pagination configuration and rendering
 * for archive, search, blog, and other paginated theme templates.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Pagination
{
    /**
     * Register the pagination module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'get_the_posts_pagination',
            [$this, 'filter_posts_pagination']
        );
    }

    /**
     * Filter generated posts pagination markup.
     *
     * Adds WooShop-specific classes while preserving the
     * WordPress pagination structure.
     *
     * @param string $output Generated pagination markup.
     *
     * @return string
     */
    public function filter_posts_pagination(string $output): string
    {
        if ($output === '') {
            return $output;
        }

        $output = str_replace(
            'class="navigation',
            'class="navigation ws-pagination',
            $output
        );

        $output = str_replace(
            'class="nav-links',
            'class="nav-links ws-pagination-links',
            $output
        );

        return $output;
    }

    /**
     * Render the WooShop posts pagination.
     *
     * This helper is intended for archive, search, blog, and
     * other templates that require paginated post navigation.
     *
     * @return void
     */
    public function render(): void
    {
        $pagination = get_the_posts_pagination(
            [
                'mid_size'           => 2,
                'prev_text'          => __('Previous', 'wooshop'),
                'next_text'          => __('Next', 'wooshop'),
                'screen_reader_text' => __('Posts navigation', 'wooshop'),
            ]
        );

        if (!is_string($pagination) || $pagination === '') {
            return;
        }

        echo wp_kses_post($pagination);
    }
}