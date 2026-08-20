<?php
/**
 * Theme Template Tags Module.
 *
 * Provides reusable template helpers used by WooShop classic templates.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WP_Post;
use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Template tags module.
 */
final class TemplateTags extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        /*
         * Template tag methods are exposed as compatibility
         * functions below so classic template files can remain
         * simple and readable.
         */
    }

    /**
     * Render post thumbnail.
     *
     * @param string $size Image size.
     * @param array<string, mixed> $attr Image attributes.
     *
     * @return void
     */
    public static function post_thumbnail(
        string $size = 'large',
        array $attr = array()
    ): void {

        if ( ! has_post_thumbnail() ) {
            return;
        }

        $default_attr = array(
            'class' => 'post-thumbnail__image',
        );

        $attr = wp_parse_args( $attr, $default_attr );

        the_post_thumbnail(
            $size,
            $attr
        );
    }

    /**
     * Render post date.
     *
     * @return void
     */
    public static function posted_on(): void {

        $time_string = sprintf(
            '<time class="entry-date published" datetime="%1$s">%2$s</time>',
            esc_attr(
                get_the_date(
                    DATE_W3C
                )
            ),
            esc_html(
                get_the_date()
            )
        );

        printf(
            '<span class="posted-on">%s</span>',
            $time_string
        );
    }

    /**
     * Render post author.
     *
     * @return void
     */
    public static function posted_by(): void {

        printf(
            '<span class="byline">%1$s <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
            esc_html__( 'by', 'wooshop' ),
            esc_url(
                get_author_posts_url(
                    (int) get_the_author_meta( 'ID' )
                )
            ),
            esc_html(
                get_the_author()
            )
        );
    }

    /**
     * Render entry footer metadata.
     *
     * @return void
     */
    public static function entry_footer(): void {

        $post_id = get_the_ID();

        if ( ! $post_id ) {
            return;
        }

        $categories = get_the_category_list(
            esc_html__( ', ', 'wooshop' ),
            '',
            $post_id
        );

        if ( $categories ) {
            printf(
                '<span class="cat-links">%1$s %2$s</span>',
                esc_html__( 'Posted in', 'wooshop' ),
                wp_kses_post( $categories )
            );
        }

        $tags = get_the_tag_list(
            '',
            esc_html__( ', ', 'wooshop' ),
            '',
            $post_id
        );

        if ( $tags ) {
            printf(
                '<span class="tags-links">%1$s %2$s</span>',
                esc_html__( 'Tagged', 'wooshop' ),
                wp_kses_post( $tags )
            );
        }

        if ( is_singular() && ! post_password_required() && comments_open() ) {
            printf(
                '<span class="comments-link">%s</span>',
                wp_kses_post(
                    get_comments_link()
                )
            );
        }

        edit_post_link(
            sprintf(
            /* translators: %s: Post title. */
                esc_html__( 'Edit %s', 'wooshop' ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }

    /**
     * Display fallback page thumbnail.
     *
     * @return void
     */
    public static function page_thumbnail(): void {

        if ( has_post_thumbnail() ) {
            self::post_thumbnail( 'large' );
        }
    }
}