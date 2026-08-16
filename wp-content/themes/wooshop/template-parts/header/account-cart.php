<?php
/**
 * Header Account and Cart
 *
 * Displays account and cart links in the site header.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-account-cart d-flex align-items-center gap-2">

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>

        <a
            class="ws-account btn btn-outline-secondary"
            href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
        >
            <?php esc_html_e( 'Account', 'wooshop' ); ?>
        </a>

        <a
            class="ws-cart btn btn-primary"
            href="<?php echo esc_url( wc_get_cart_url() ); ?>"
        >
            <?php esc_html_e( 'Cart', 'wooshop' ); ?>
        </a>

    <?php endif; ?>

</div>