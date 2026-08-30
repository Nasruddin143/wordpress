<?php
/**
 * WooShop Comments Module
 *
 * Controls comment form defaults, pagination, and
 * provides a reusable render helper for template parts.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WP_Comment;
use WP_Comment_Query;
use WP_Post;

defined('ABSPATH') || exit;

/**
 * Class Comments
 */
final class Comments extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('comment_form_defaults', [$this, 'form_defaults']);
        add_filter('comment_form_fields', [$this, 'reorder_fields']);
        add_filter('comment_reply_link', [$this, 'reply_link'], 10, 4);
        add_action('pre_get_comments', [$this, 'exclude_pingbacks']);
    }

    /**
     * Customize comment form labels and markup.
     *
     * @param array<string, mixed> $defaults Default form arguments.
     *
     * @return array<string, mixed>
     */
    public function form_defaults(array $defaults): array
    {
        $defaults['title_reply'] = __('Leave a Comment', 'wooshop');
        $defaults['title_reply_before'] = '<h2 class="comment-reply-title">';
        $defaults['title_reply_after'] = '</h2>';
        $defaults['cancel_reply_before'] = ' <small class="comment-reply-cancel">';
        $defaults['cancel_reply_after'] = '</small>';
        $defaults['label_submit'] = __('Post Comment', 'wooshop');
        $defaults['submit_button'] = '<button type="submit" name="%1$s" id="%2$s" class="%3$s button button--primary">%4$s</button>';
        $defaults['submit_field'] = '<div class="form-submit">%1$s %2$s</div>';
        $defaults['comment_notes_before'] = '';
        $defaults['comment_notes_after'] = '';

        return $defaults;
    }

    /**
     * Move the comment textarea above name/email fields
     * (textarea → author → email → url).
     *
     * @param array<string, string> $fields Comment form fields.
     *
     * @return array<string, string>
     */
    public function reorder_fields(array $fields): array
    {
        $order = ['author', 'email', 'url', 'cookies'];
        $reordered = [];

        foreach ($order as $key) {
            if (isset($fields[$key])) {
                $reordered[$key] = $fields[$key];
                unset($fields[$key]);
            }
        }

        return array_merge($reordered, $fields);
    }

    /**
     * Wrap the reply link in a themed container.
     *
     * @param string $link HTML reply link.
     * @param mixed $args Reply link arguments.
     * @param WP_Comment $comment Comment object.
     * @param WP_Post $post Post object.
     *
     * @return string
     */
    public function reply_link(string $link, mixed $args, WP_Comment $comment, WP_Post $post): string
    {
        return '<span class="comment-reply-link-wrap">' . $link . '</span>';
    }

    /**
     * Exclude pingbacks and trackbacks from comment queries
     * on singular posts so the count and list stay clean.
     *
     * @param WP_Comment_Query $query Comment query object.
     *
     * @return void
     */
    public function exclude_pingbacks(WP_Comment_Query $query): void
    {
        if (!is_singular()) {
            return;
        }

        $query->query_vars['type__not_in'] = array_merge(
            (array)($query->query_vars['type__not_in'] ?? []),
            ['pingback', 'trackback']
        );
    }

    /**
     * Render the comments list and form.
     *
     * Call from comments.php template:
     *
     *   \WooShop\Modules\Theme\Comments::render();
     *
     * @return void
     */
    public static function render(): void
    {
        if (post_password_required()) {
            return;
        }

        $comment_count = get_comments_number();

        echo '<section id="comments" class="comments-area">';

        // Comments list.
        if (have_comments()) {
            echo '<h2 class="comments-title">';
            printf(
            /* translators: 1: comment count, 2: post title */
                esc_html(
                    _nx(
                        '%1$s thought on &ldquo;%2$s&rdquo;',
                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                        $comment_count,
                        'comments title',
                        'wooshop'
                    )
                ),
                number_format_i18n($comment_count),
                '<span>' . get_the_title() . '</span>'
            );
            echo '</h2>';

            echo '<ol class="comment-list">';
            wp_list_comments([
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback' => null, // Use default; override with a custom Walker if needed.
            ]);
            echo '</ol>';

            the_comments_pagination([
                'prev_text' => __('← Older Comments', 'wooshop'),
                'next_text' => __('Newer Comments →', 'wooshop'),
            ]);
        }

        // Comment form.
        if (comments_open()) {
            comment_form();
        } else {
            echo '<p class="no-comments">' . esc_html__('Comments are closed.', 'wooshop') . '</p>';
        }

        echo '</section>';
    }
}