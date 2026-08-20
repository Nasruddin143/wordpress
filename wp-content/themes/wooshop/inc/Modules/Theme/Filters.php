<?php
/**
 * WooShop Theme Filters Module
 *
 * Registers general WordPress filters used by the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WP_Post;

defined('ABSPATH') || exit;

final class Filters
{
    /**
     * Register the theme filters module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'body_class',
            [$this, 'filter_body_class']
        );

        add_filter(
            'post_class',
            [$this, 'filter_post_class']
        );

        add_filter(
            'get_search_form',
            [$this, 'filter_search_form']
        );
    }

    /**
     * Filter the body classes.
     *
     * Adds the WooShop theme class to the document body.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function filter_body_class(array $classes): array
    {
        $classes[] = 'wooshop';

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Filter post classes.
     *
     * Adds a consistent WooShop post class.
     *
     * @param array<int, string> $classes Existing post classes.
     * @param array<int, mixed>  $class   Additional classes.
     * @param int|WP_Post|null   $post    Post object or post ID.
     *
     * @return array<int, string>
     */
    public function filter_post_class(
        array $classes,
        array $class = [],
        mixed $post = null
    ): array {
        $classes[] = 'ws-post';

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Filter the WordPress search form.
     *
     * Adds the WooShop search-form class to the generated form.
     *
     * @param string $form Generated search form.
     *
     * @return string
     */
    public function filter_search_form(string $form): string
    {
        return str_replace(
            'search-form',
            'search-form ws-search-form',
            $form
        );
    }
}