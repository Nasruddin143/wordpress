<?php
/**
 * Attachment Template
 * SEO Optimized + Bootstrap 5
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main attachment-page">

<?php if (have_posts()) : while (have_posts()) : the_post();

    $attachment_id = get_the_ID();
    $mime_type     = get_post_mime_type($attachment_id);
    $file_url      = wp_get_attachment_url($attachment_id);
    $file_path     = get_attached_file($attachment_id);
    $file_size     = ($file_path && file_exists($file_path)) ? size_format(filesize($file_path)) : '';
    $image_src     = wp_get_attachment_image_src($attachment_id, 'large');
    $upload_date   = get_the_date('c');
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!--  TITLE  -->
            <header class="entry-header mb-4">
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <p class="text-muted small">
                    <?php esc_html_e('Uploaded on', 'king_tailors'); ?>
                    <?php echo esc_html(get_the_date()); ?>
                    <?php if ($mime_type) : ?>
                        | <?php echo esc_html($mime_type); ?>
                    <?php endif; ?>
                    <?php if ($file_size) : ?>
                        | <?php echo esc_html($file_size); ?>
                    <?php endif; ?>
                </p>
            </header>

            <!--  PREVIEW  -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">

                    <?php if (wp_attachment_is_image($attachment_id) && $image_src) : ?>

                        <img
                            src="<?php echo esc_url($image_src[0]); ?>"
                            class="img-fluid rounded"
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            loading="lazy"
                        >

                    <?php else : ?>

                        <p class="mb-3 text-muted">
                            <?php esc_html_e('Preview not available for this file type.', 'king_tailors'); ?>
                        </p>

                        <a href="<?php echo esc_url($file_url); ?>"
                           class="btn btn-primary"
                           download>
                            ⬇ <?php esc_html_e('Download File', 'king_tailors'); ?>
                        </a>

                    <?php endif; ?>

                </div>
            </div>

            <!--  DESCRIPTION  -->
            <?php if (has_excerpt()) : ?>
                <section class="entry-summary mb-4">
                    <h2 class="h5"><?php esc_html_e('File Description', 'king_tailors'); ?></h2>
                    <?php echo wp_kses_post(get_the_excerpt()); ?>
                </section>
            <?php endif; ?>

            <!--  CONTENT  -->
            <article class="entry-content mb-4">
                <?php the_content(); ?>
            </article>

            <!--  NAVIGATION  -->
            <?php if (wp_attachment_is_image($attachment_id)) : ?>
                <nav class="d-flex justify-content-between">
                    <div><?php previous_image_link(false, '← ' . esc_html__('Previous', 'king_tailors')); ?></div>
                    <div><?php next_image_link(false, esc_html__('Next', 'king_tailors') . ' →'); ?></div>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
