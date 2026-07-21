<?php
/**
 * Automatic Internal Linking System (Optimized)
 * @package king_tailors
 */

if (!defined('ABSPATH')) {
    exit;
}

class KT_Internal_Linking
{

    private $max_links;
    private $min_words;

    public function __construct()
    {

        $this->max_links = get_option('kt_max_internal_links', 5);
        $this->min_words = get_option('kt_min_word_count', 250);

        add_filter('the_content', [$this, 'auto_link_content'], 20);
        add_filter('the_excerpt', [$this, 'auto_link_content'], 20);
    }

    /**
     * Main linking function
     */
    public function auto_link_content($content)
    {

        if (is_admin() || !is_singular()) {
            return $content;
        }

        $post_id = get_the_ID();

        if (!$post_id) {
            return $content;
        }

        if (str_word_count(strip_tags($content)) < $this->min_words) {
            return $content;
        }

        $related_posts = $this->get_related_posts($post_id);

        if (empty($related_posts)) {
            return $content;
        }

        return $this->insert_links($content, $related_posts);
    }

    /**
     * Get related posts (cached)
     */
    private function get_related_posts($post_id)
    {

        $cache_key = 'kt_related_posts_' . $post_id;
        $cached = get_transient($cache_key);

        if ($cached !== false) {
            return $cached;
        }

        $categories = wp_get_post_terms($post_id, 'category', ['fields' => 'ids']);
        $tags = wp_get_post_terms($post_id, 'post_tag', ['fields' => 'ids']);

        $args = [
            'post_type' => get_post_type($post_id),
            'posts_per_page' => 10,
            'post__not_in' => [$post_id],
            'ignore_sticky_posts' => true,
            'orderby' => 'rand',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $categories
                ],
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $tags
                ]
            ]
        ];

        $posts = get_posts($args);
        $related = [];

        foreach ($posts as $post) {

            $related[] = [
                'id' => $post->ID,
                'title' => get_the_title($post->ID),
                'url' => get_permalink($post->ID),
                'keywords' => $this->extract_keywords(get_the_title($post->ID))
            ];
        }

        set_transient($cache_key, $related, 6 * HOUR_IN_SECONDS);

        return $related;
    }

    /**
     * Extract keywords from title
     */
    private function extract_keywords($title)
    {

        $stop_words = [
            'the',
            'a',
            'an',
            'and',
            'or',
            'but',
            'in',
            'on',
            'at',
            'to',
            'for',
            'of',
            'with',
            'by',
            'from',
            'this',
            'that',
            'these',
            'those',
            'best',
            'top',
            'new',
            'your',
            'our'
        ];

        $words = explode(' ', strtolower($title));
        $keywords = [];

        foreach ($words as $word) {

            $word = preg_replace('/[^a-z0-9]/', '', $word);

            if (!in_array($word, $stop_words) && strlen($word) > 3) {
                $keywords[] = $word;
            }
        }

        return array_unique($keywords);
    }

    /**
     * Insert links safely
     */
    private function insert_links($content, $related_posts)
    {

        $links_added = 0;
        $linked = [];

        // Split content to avoid linking inside existing links
        $parts = preg_split('/(<a.*?>.*?<\/a>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($parts as &$part) {

            if (stripos($part, '<a') === 0) {
                continue;
            }

            foreach ($related_posts as $post) {

                if ($links_added >= $this->max_links) {
                    break 2;
                }

                $title = $post['title'];

                if (in_array($title, $linked)) {
                    continue;
                }

                $pattern = '/\b' . preg_quote($title, '/') . '\b/i';

                if (preg_match($pattern, $part)) {

                    $anchor = $this->random_anchor($title);

                    $link = sprintf(
                        '<a href="%s" class="internal-link" title="%s">%s</a>',
                        esc_url($post['url']),
                        esc_attr(sprintf(__('Read more about %s', 'king_tailors'), $title)),
                        esc_html($anchor)
                    );

                    $part = preg_replace($pattern, $link, $part, 1);

                    $links_added++;
                    $linked[] = $title;

                    continue;
                }

                foreach ($post['keywords'] as $keyword) {

                    if ($links_added >= $this->max_links) {
                        break 3;
                    }

                    if (in_array($keyword, $linked)) {
                        continue;
                    }

                    $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';

                    if (preg_match($pattern, $part)) {

                        $link = sprintf(
                            '<a href="%s" class="internal-link" title="%s">$0</a>',
                            esc_url($post['url']),
                            esc_attr(sprintf(__('Learn more about %s', 'king_tailors'), $title))
                        );

                        $part = preg_replace($pattern, $link, $part, 1);

                        $links_added++;
                        $linked[] = $keyword;

                        break;
                    }
                }
            }
        }

        return implode('', $parts);
    }

    /**
     * Anchor text variation
     */
    private function random_anchor($title)
    {

        $anchors = [
            $title,
            'best ' . $title,
            'learn about ' . $title,
            $title . ' details'
        ];

        return $anchors[array_rand($anchors)];
    }

}

new KT_Internal_Linking();


/**
 * Admin Settings
 */

function kt_internal_link_settings_menu()
{

    add_theme_page(
        __('Internal Linking', 'king_tailors'),
        __('Internal Linking', 'king_tailors'),
        'manage_options',
        'kt-internal-linking',
        'kt_internal_link_settings_page'
    );

}

add_action('admin_menu', 'kt_internal_link_settings_menu');


function kt_internal_link_settings_page()
{

    ?>

            <div class="wrap">
                <h1>
                    <?php _e('Internal Linking Settings', 'king_tailors'); ?>
                </h1>

                <form method="post" action="options.php">

                    <?php settings_fields('kt_internal_link_settings'); ?>

                    <table class="form-table">

                        <tr>
                            <th>
                                <?php _e('Maximum Links', 'king_tailors'); ?>
                            </th>
                            <td>
                                <input type="number" name="kt_max_internal_links"
                                    value="<?php echo esc_attr(get_option('kt_max_internal_links', 5)); ?>" min="1" max="20">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <?php _e('Minimum Word Count', 'king_tailors'); ?>
                            </th>
                            <td>
                                <input type="number" name="kt_min_word_count"
                                    value="<?php echo esc_attr(get_option('kt_min_word_count', 250)); ?>">
                            </td>
                        </tr>

                    </table>

                    <?php submit_button(); ?>

                </form>
            </div>

            <?php
}


function kt_register_internal_link_settings()
{

    register_setting('kt_internal_link_settings', 'kt_max_internal_links');
    register_setting('kt_internal_link_settings', 'kt_min_word_count');

}

add_action('admin_init', 'kt_register_internal_link_settings');