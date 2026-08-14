<?php
/**
 * Post Author
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$author_id = (int) get_the_author_meta( 'ID' );

if ( ! $author_id ) {
    return;
}
?>

<span class="ws-meta-author">

    <?php esc_html_e( 'By', 'wooshop' ); ?>

    <a
        href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"
        rel="author">

        <?php
        echo esc_html(
            get_the_author()
        );
        ?>

    </a>

</span>