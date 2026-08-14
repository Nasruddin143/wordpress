<?php
/**
 * Post Tags
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$tags = get_the_tags();

if ( empty( $tags ) ) {
    return;
}
?>

<div class="ws-meta-tags">

    <span class="ws-meta-label">

        <?php
        esc_html_e(
            'Tags:',
            'wooshop'
        );
        ?>

    </span>

    <?php foreach ( $tags as $tag ) : ?>

        <a
            class="ws-meta-tag"
            href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">

            <?php echo esc_html( $tag->name ); ?>

        </a>

    <?php endforeach; ?>

</div>