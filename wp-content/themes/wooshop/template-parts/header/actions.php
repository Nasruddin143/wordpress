<?php
/**
 * Header Actions
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-header-actions">

    <a
            class="ws-header-action"
            href="<?php echo esc_url( wp_login_url() ); ?>"
            aria-label="<?php esc_attr_e( 'My Account', 'wooshop' ); ?>"
    >

        <span class="dashicons dashicons-admin-users"></span>

        <span class="ws-header-action__label">

            <?php esc_html_e( 'Account', 'wooshop' ); ?>

        </span>

    </a>

    <button
            class="ws-header-action"
            type="button"
            aria-label="<?php esc_attr_e( 'Wishlist', 'wooshop' ); ?>"
    >

        <span class="dashicons dashicons-heart"></span>

        <span class="ws-header-action__label">

            <?php esc_html_e( 'Wishlist', 'wooshop' ); ?>

        </span>

    </button>

    <button
            class="ws-header-action"
            type="button"
            aria-label="<?php esc_attr_e( 'Compare', 'wooshop' ); ?>"
    >

        <span class="dashicons dashicons-randomize"></span>

        <span class="ws-header-action__label">

            <?php esc_html_e( 'Compare', 'wooshop' ); ?>

        </span>

    </button>

    <button
            class="ws-header-action"
            type="button"
            aria-label="<?php esc_attr_e( 'Cart', 'wooshop' ); ?>"
    >

        <span class="dashicons dashicons-cart"></span>

        <span class="ws-header-action__count">0</span>

    </button>

</div>