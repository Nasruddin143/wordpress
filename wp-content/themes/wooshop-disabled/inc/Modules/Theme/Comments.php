<?php
/**
 * WooShop Comments Module
 *
 * Provides comment and discussion functionality for the WooShop
 * theme while keeping comment markup lightweight and accessible.
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
 * Class Comments
 *
 * Handles WordPress comment functionality.
 */
final class Comments extends Module {

    /**
     * Register comment functionality.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'comment_form_defaults',
            [$this, 'filter_comment_form_defaults']
        );

        add_filter(
            'comment_form_field_comment',
            [$this, 'filter_comment_field']
        );

        add_filter(
            'comment_reply_link_args',
            [$this, 'filter_reply_link_args']
        );

        add_filter(
            'comment_class',
            [$this, 'filter_comment_class'],
            10,
            5
        );

        add_filter(
            'get_avatar',
            [$this, 'filter_avatar'],
            10,
            6
        );

        add_filter(
            'comments_popup_link_attributes',
            [$this, 'filter_comments_link_attributes']
        );
    }

    /**
     * Configure the default comment form.
     *
     * @param array<string, mixed> $defaults Comment form defaults.
     *
     * @return array<string, mixed>
     */
    public function filter_comment_form_defaults(
        array $defaults
    ): array {

        $defaults['class_form'] = 'comment-form';

        $defaults['class_submit'] = 'btn btn-primary';

        $defaults['title_reply'] = esc_html__(
            'Leave a Comment',
            'wooshop'
        );

        $defaults['title_reply_to'] = esc_html__(
            'Leave a Reply to %s',
            'wooshop'
        );

        $defaults['cancel_reply_link'] = esc_html__(
            'Cancel Reply',
            'wooshop'
        );

        $defaults['label_submit'] = esc_html__(
            'Post Comment',
            'wooshop'
        );

        $defaults['submit_field'] = (
        '<p class="form-submit">%1$s %2$s</p>'
        );

        return $defaults;
    }

    /**
     * Configure the main comment textarea.
     *
     * @param string $field Comment textarea field.
     *
     * @return string
     */
    public function filter_comment_field(
        string $field
    ): string {

        $field = str_replace(
            '<textarea',
            '<textarea aria-label="' .
            esc_attr__(
                'Comment',
                'wooshop'
            ) .
            '"',
            $field
        );

        return $field;
    }

    /**
     * Configure the comment reply link.
     *
     * @param array<string, mixed> $args Reply link arguments.
     *
     * @return array<string, mixed>
     */
    public function filter_reply_link_args(
        array $args
    ): array {

        $args['class'] = 'comment-reply-link btn btn-sm btn-outline-secondary';

        $args['reply_text'] = esc_html__(
            'Reply',
            'wooshop'
        );

        return $args;
    }

    /**
     * Add WooShop classes to comments.
     *
     * @param array<int, string> $classes Existing comment classes.
     * @param string             $css_class Additional CSS class.
     * @param int                $comment_id Comment ID.
     * @param int                $post_id Post ID.
     * @param int                $commenter_id Commenter ID.
     *
     * @return array<int, string>
     */
    public function filter_comment_class(
        array $classes,
        string $css_class,
        int $comment_id,
        int $post_id,
        int $commenter_id
    ): array {

        $classes[] = 'ws-comment';

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Improve avatar accessibility.
     *
     * @param string     $avatar Avatar HTML.
     * @param mixed      $id_or_email User identifier.
     * @param int        $size Avatar size.
     * @param string     $default Default avatar URL.
     * @param string     $alt Avatar alt text.
     * @param array      $args Avatar arguments.
     *
     * @return string
     */
    public function filter_avatar(
        string $avatar,
        mixed $id_or_email,
        int $size,
        string $default,
        string $alt,
        array $args
    ): string {

        if ('' !== $alt) {
            return $avatar;
        }

        $avatar = str_replace(
            '<img',
            '<img alt="' .
            esc_attr__(
                'Comment author',
                'wooshop'
            ) .
            '"',
            $avatar
        );

        return $avatar;
    }

    /**
     * Add an accessible label to comments links.
     *
     * @param string $attributes Existing HTML attributes.
     *
     * @return string
     */
    public function filter_comments_link_attributes(
        string $attributes
    ): string {

        if (
            false === strpos(
                $attributes,
                'aria-label='
            )
        ) {
            $attributes .= ' aria-label="' .
                esc_attr__(
                    'View comments',
                    'wooshop'
                ) .
                '"';
        }

        return trim($attributes);
    }
}