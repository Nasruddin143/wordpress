<?php
/**
 * Header Actions
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-header-actions d-flex align-items-center gap-2">

    <a
            href="<?php echo esc_url( wp_login_url() ); ?>"
            class="btn btn-link text-decoration-none text-dark p-2">

        <span aria-hidden="true">♙</span>

        <span class="visually-hidden">
            <?php esc_html_e( 'Account', 'wooshop' ); ?>
        </span>

    </a>

    <a
            href="#"
            class="btn btn-link text-decoration-none text-dark p-2">

        <span aria-hidden="true">♡</span>

        <span class="visually-hidden">
            <?php esc_html_e( 'Wishlist', 'wooshop' ); ?>
        </span>

    </a>

</div>