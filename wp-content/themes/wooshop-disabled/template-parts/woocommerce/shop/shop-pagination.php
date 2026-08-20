<?php
/**
 * Shop Pagination
 *
 * Displays WooCommerce product archive pagination.
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
    class="ws-shop-pagination py-4"
    aria-label="<?php esc_attr_e( 'Shop pagination', 'wooshop' ); ?>"
>

    <ul class="pagination justify-content-center mb-0">

        <?php foreach ( $pagination as $link ) : ?>

            <li class="page-item">

                <?php echo wp_kses_post( $link ); ?>

            </li>

        <?php endforeach; ?>

    </ul>

</nav>