<?php
/**
 * WooShop Theme Filters Module
 *
 * WordPress content filters: excerpt length, read-more
 * link, caption markup, and search form output.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Filters
 */
final class Filters extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('excerpt_length',       [$this, 'excerpt_length'], 20);
        add_filter('excerpt_more',         [$this, 'excerpt_more']);
        add_filter('the_content_more_tag', [$this, 'content_more_tag']);
        add_filter('img_caption_shortcode_width', '__return_false');
        //add_filter('get_search_form',      [$this, 'search_form']);
        add_filter('wp_title',             [$this, 'wp_title'], 10, 2);
    }

    /**
     * Set excerpt word count from theme.php config.
     *
     * @param int $length Current word count.
     *
     * @return int
     */
    public function excerpt_length(int $length): int
    {
        $config = $this->container->get('config')->get('theme');

        return (int) ($config['excerpt']['length'] ?? 30);
    }

    /**
     * Replace the default "[…]" excerpt suffix with a read-more link.
     *
     * @param string $more Current suffix.
     *
     * @return string
     */
    public function excerpt_more(string $more): string
    {
        if (!is_singular()) {
            return sprintf(
                ' <a class="read-more" href="%s">%s</a>',
                esc_url(get_permalink()),
                esc_html__('Read More', 'wooshop')
            );
        }

        return '';
    }

    /**
     * Replace the <!--more--> tag output with themed markup.
     *
     * @param string $tag Current more tag HTML.
     *
     * @return string
     */
    public function content_more_tag(string $tag): string
    {
        return sprintf(
            '<a class="more-link" href="%s#more-%d">%s</a>',
            esc_url(get_permalink()),
            get_the_ID(),
            esc_html__('Continue Reading', 'wooshop')
        );
    }

    /**
     * Output a custom accessible search form.
     *
     * @param string $form Current search form HTML.
     *
     * @return string
     */
    public function search_form(string $form): string
    {
        $unique_id = wp_unique_id('search-form-');

        return sprintf(
            '<form role="search" method="get" class="search-form" action="%s">
                <label for="%s" class="screen-reader-text">%s</label>
                <input type="search" id="%s" class="search-field" placeholder="%s" value="%s" name="s" />
                <button type="submit" class="search-submit">
                    <span class="screen-reader-text">%s</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </button>
            </form>',
            esc_url(home_url('/')),
            esc_attr($unique_id),
            esc_html__('Search for:', 'wooshop'),
            esc_attr($unique_id),
            esc_attr__('Search &hellip;', 'wooshop'),
            esc_attr(get_search_query()),
            esc_html__('Search', 'wooshop')
        );
    }

    /**
     * Append the site name to the <title> tag on inner pages.
     *
     * @param string $title Current title.
     * @param string $sep   Separator character.
     *
     * @return string
     */
    public function wp_title(string $title, string $sep): string
    {
        if (is_feed()) {
            return $title;
        }

        $title .= get_bloginfo('name', 'display');

        $site_description = get_bloginfo('description', 'display');

        if ($site_description && (is_home() || is_front_page())) {
            $title .= " $sep $site_description";
        }

        return $title;
    }
}