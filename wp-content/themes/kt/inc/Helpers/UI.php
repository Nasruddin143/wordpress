<?php

namespace KT\Helpers;

defined('ABSPATH') || exit;


class UI
{
    public function __construct()
    {
        add_filter('get_the_archive_title', [$this, 'clean_archive_title']);
    }

    /**
     * ----------------------------------------
     * Social Share Buttons
     * ----------------------------------------
     */
    public static function social_share()
    {

        $share_url   = urlencode(home_url(add_query_arg([], wp_unslash($_SERVER['REQUEST_URI']))));
        $share_title = urlencode(wp_get_document_title());

        $socials = [
            [
                'name'  => __('WhatsApp', 'kt'),
                'icon'  => 'bi-whatsapp',
                'url'   => "https://wa.me/?text={$share_url}"
            ],
            [
                'name'  => __('Facebook', 'kt'),
                'icon'  => 'bi-facebook',
                'url'   => "https://www.facebook.com/sharer/sharer.php?u={$share_url}"
            ],
            [
                'name'  => __('X (Twitter)', 'kt'),
                'icon'  => 'bi-twitter-x',
                'url'   => "https://twitter.com/intent/tweet?text={$share_title}&url={$share_url}"
            ],
            [
                'name'  => __('LinkedIn', 'kt'),
                'icon'  => 'bi-linkedin',
                'url'   => "https://www.linkedin.com/shareArticle?mini=true&url={$share_url}&title={$share_title}"
            ]
        ];

        ob_start(); ?>

        <nav class="post-share d-flex flex-wrap align-items-center gap-2"
            role="navigation"
            aria-label="<?php echo esc_attr__('Social sharing options', 'kt'); ?>">

            <span class="me-2 d-none d-sm-inline">
                <?php echo esc_html__('Share:', 'kt'); ?>
            </span>

            <?php foreach ($socials as $social): ?>
                <a href="<?php echo esc_url($social['url']); ?>"
                    title="<?php echo esc_attr(sprintf(__('Share on %s', 'kt'), $social['name'])); ?>"
                    target="_blank"
                    rel="noopener nofollow"
                    class="link-offset-2 link-underline link-underline-opacity-0 link-dark"
                    aria-label="<?php echo esc_attr(sprintf(__('Share on %s', 'kt'), $social['name'])); ?>">

                    <i class="bi <?php echo esc_attr($social['icon']); ?> fs-5" aria-hidden="true"></i>
                </a>
            <?php endforeach; ?>

            <button type="button"
                onclick="window.print();"
                class="btn btn-link link-dark p-0 border-0"
                aria-label="<?php echo esc_attr__('Print this page', 'kt'); ?>"
                title="<?php echo esc_attr__('Print this page', 'kt'); ?>">

                <i class="bi bi-printer fs-5" aria-hidden="true"></i>
            </button>

        </nav>

<?php return ob_get_clean();
    }


    /**
     * -------------------------------------------------
     * REMOVE PREFIX FROM ARCHIVE TITLES
     * -------------------------------------------------
     */
    public static function clean_archive_title($title)
    {
        if (is_category()) {
            return apply_filters('kt_archive_title_category', single_cat_title('', false));
        }

        if (is_tag()) {
            return apply_filters('kt_archive_title_tag', single_tag_title('', false));
        }

        if (is_author()) {
            return apply_filters(
                'kt_archive_title_author',
                '<span class="vcard">' . get_the_author() . '</span>'
            );
        }

        if (is_tax()) {
            return apply_filters('kt_archive_title_tax', single_term_title('', false));
        }

        if (is_post_type_archive()) {
            return apply_filters('kt_archive_title_cpt', post_type_archive_title('', false));
        }

        return $title;
    }
}
