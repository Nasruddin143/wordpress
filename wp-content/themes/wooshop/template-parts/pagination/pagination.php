<?php
/**
 * Post Pagination
 *
 * Displays Bootstrap-compatible WordPress pagination.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$pagination = paginate_links(
    array(
        'type'      => 'array',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    )
);

if ( empty( $pagination ) ) {
    return;
}
?>

<nav
    class="ws-pagination"
    aria-label="<?php esc_attr_e( 'Posts navigation', 'wooshop' ); ?>"
>

    <ul class="pagination justify-content-center">

        <?php foreach ( $pagination as $link ) : ?>

            <li class="page-item">

                <?php echo wp_kses_post( $link ); ?>

            </li>

        <?php endforeach; ?>

    </ul>

</nav>