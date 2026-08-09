<?php
/**
 * Footer Social Links
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

/*
 * Later these can come from Theme Settings.
 */
$social_links = apply_filters(
        'wooshop_footer_social_links',
        [
                'facebook' => get_theme_mod('facebook_url'),
                'instagram' => get_theme_mod('instagram_url'),
                'x' =>  '',//get_theme_mod('twitter_url'),
                'youtube' => '', // get_theme_mod('youtube_url'),
                'linkedin' => '', //get_theme_mod('linkedin_url'),
        ]
);

$social_links = array_filter($social_links);

if (empty($social_links)) {
    return;
}
?>

<div
        class="ws-footer-social"
        aria-label="<?php esc_attr_e('Follow Us', 'wooshop'); ?>">

    <span class="ws-social-title">
        <?php esc_html_e('Follow Us', 'wooshop'); ?>
    </span>

    <ul class="ws-social-list">

        <?php foreach ($social_links as $network => $url) : ?>

            <li class="ws-social-item">

                <a
                        href="<?php echo esc_url($url); ?>"
                        class="ws-social-link"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php echo esc_attr(ucfirst($network)); ?>">

                    <?php
                    if (function_exists('wooshop_icon')) {

                        echo wooshop_icon(
                                $network,
                                [
                                        'class' => 'ws-social-icon',
                                ]
                        );

                    }
                    ?>

                </a>

            </li>

        <?php endforeach; ?>

    </ul>

</div>