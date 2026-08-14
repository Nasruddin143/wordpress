<?php
/**
 * Post Categories
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$categories = get_the_category();

if ( empty( $categories ) ) {
    return;
}
?>

<span class="ws-meta-categories">

    <?php esc_html_e( 'Filed under:', 'wooshop' ); ?>

    <?php foreach ( $categories as $index => $category ) : ?>

        <a
            href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">

            <?php echo esc_html( $category->name ); ?>

        </a>

        <?php if ( $index < count( $categories ) - 1 ) : ?>

            <span aria-hidden="true">, </span>

        <?php endif; ?>

    <?php endforeach; ?>

</span>