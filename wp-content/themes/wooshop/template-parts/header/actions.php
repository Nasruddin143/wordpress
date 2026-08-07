<?php
/**
 * Header Actions
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-header-actions">

    <!-- Account -->
    <a class="ws-header-action"
       href="<?php echo esc_url( wp_login_url() ); ?>"
       aria-label="<?php esc_attr_e( 'My Account', 'wooshop' ); ?>">

        <?php echo wooshop_icon( 'user' ); ?>

        <span class="ws-action-label">

            <?php esc_html_e( 'Account', 'wooshop' ); ?>

        </span>

    </a>

    <!-- Wishlist -->
    <a class="ws-header-action"
       href="#"
       aria-label="<?php esc_attr_e( 'Wishlist', 'wooshop' ); ?>">

        <?php echo wooshop_icon( 'wishlist' ); ?>

        <span class="ws-action-count">0</span>

    </a>

    <!-- Cart -->
    <a class="ws-header-action"
       href="<?php echo wc_get_cart_url() ?>"
       aria-label="<?php esc_attr_e( 'Cart', 'wooshop' ); ?>">

        <?php echo wooshop_icon( 'cart' ); ?>

        <span class="ws-action-count"><?php echo WC()->cart->get_cart_contents_count() ?></span>

    </a>

</div>