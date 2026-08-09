<?php
/**
 * Post Meta
 *
 * Displays post metadata.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-post-meta">

    <?php
    get_template_part(
        'template-parts/meta/author'
    );
    ?>

    <span
        class="ws-meta-separator"
        aria-hidden="true">
        ·
    </span>

    <?php
    get_template_part(
        'template-parts/meta/date'
    );
    ?>

    <?php
    get_template_part(
        'template-parts/meta/modified-date'
    );
    ?>

    <?php if ( get_the_category() ) : ?>

        <span
            class="ws-meta-separator"
            aria-hidden="true">
            ·
        </span>

        <?php
        get_template_part(
            'template-parts/meta/categories'
        );
        ?>

    <?php endif; ?>

    <?php if ( comments_open() || get_comments_number() ) : ?>

        <span
            class="ws-meta-separator"
            aria-hidden="true">
            ·
        </span>

        <?php
        get_template_part(
            'template-parts/meta/comments'
        );
        ?>

    <?php endif; ?>

</div>