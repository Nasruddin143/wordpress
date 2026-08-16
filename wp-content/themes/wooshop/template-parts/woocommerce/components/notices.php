<?php
/**
 * WooCommerce Notices
 *
 * Displays WooCommerce notices using Bootstrap alert components.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_notices' ) ) {
    return;
}

$notices = wc_get_notices();

if ( empty( $notices ) ) {
    return;
}
?>

<div class="ws-woocommerce-notices" aria-live="polite">

    <?php foreach ( $notices as $notice_type => $notice_items ) : ?>

        <?php
        $alert_class = match ( $notice_type ) {
            'error'   => 'alert-danger',
            'success' => 'alert-success',
            default   => 'alert-info',
        };
        ?>

        <div
            class="alert <?php echo esc_attr( $alert_class ); ?>"
            role="<?php echo 'error' === $notice_type ? 'alert' : 'status'; ?>"
        >

            <?php foreach ( $notice_items as $notice ) : ?>

                <div class="ws-woocommerce-notice">

                    <?php echo wp_kses_post( $notice['notice'] ); ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endforeach; ?>

</div>