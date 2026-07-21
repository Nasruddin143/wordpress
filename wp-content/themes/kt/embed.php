<?php
/**
 * The template for displaying embedded content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#embed
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit; ?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    /**
     * WordPress embed styles & scripts
     */
    wp_head();
    ?>
</head>

<body <?php body_class('wp-embed-body'); ?>>

<article <?php post_class('wp-embed p-3'); ?>>

    <!-- ===================== EMBED HEADER ===================== -->
    <header class="wp-embed-header mb-3 text-center">

        <?php
        // Site Logo (fallback to site name)
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        } else {
            ?>
            <p class="site-title h6 mb-1">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </p>
            <?php
        }
        ?>

    </header>

    <!-- ===================== EMBED CONTENT ===================== -->
    <div class="wp-embed-content mb-3">

        <h1 class="wp-embed-heading h5 mb-2">
            <a href="<?php the_permalink(); ?>" target="_top" rel="noopener">
                <?php the_title(); ?>
            </a>
        </h1>

        <div class="wp-embed-excerpt text-muted small">
            <?php
            if (has_excerpt()) {
                the_excerpt();
            } else {
                echo wp_trim_words(get_the_content(), 30);
            }
            ?>
        </div>

    </div>

    <!-- ===================== EMBED FOOTER ===================== -->
    <footer class="wp-embed-footer d-flex justify-content-between align-items-center small text-muted">

        <span class="embed-meta">
            <?php echo esc_html(get_the_date()); ?>
        </span>

        <a href="<?php the_permalink(); ?>" target="_top" class="embed-read-more text-decoration-none">
            <?php esc_html_e('Read more →', 'king_tailors'); ?>
        </a>

    </footer>

</article>

<?php wp_footer(); ?>
</body>
</html>
