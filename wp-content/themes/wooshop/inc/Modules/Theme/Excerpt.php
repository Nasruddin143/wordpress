<?php
/**
 * WooShop Excerpt Module
 *
 * Provides centralized control over WordPress post excerpts,
 * including excerpt length and excerpt ending.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Excerpt
{
    /**
     * Default excerpt length in words.
     *
     * @var int
     */
    private int $excerpt_length = 30;

    /**
     * Default excerpt ending.
     *
     * @var string
     */
    private string $excerpt_more = '…';

    /**
     * Register the excerpt module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'excerpt_length',
            [$this, 'filter_excerpt_length'],
            10
        );

        add_filter(
            'excerpt_more',
            [$this, 'filter_excerpt_more'],
            10
        );
    }

    /**
     * Filter the default excerpt length.
     *
     * @param int $length Current excerpt length.
     *
     * @return int
     */
    public function filter_excerpt_length(int $length): int
    {
        return $this->excerpt_length;
    }

    /**
     * Filter the default excerpt ending.
     *
     * @param string $more Current excerpt ending.
     *
     * @return string
     */
    public function filter_excerpt_more(string $more): string
    {
        return $this->excerpt_more;
    }
}