<?php
/**
 * WooCommerce Quantity Control
 *
 * Provides the reusable quantity input wrapper.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $input_name, $input_value ) ) {
    return;
}
?>

<div class="ws-quantity-control">

    <label
        class="visually-hidden"
        for="<?php echo esc_attr( $input_name ); ?>"
    >
        <?php esc_html_e( 'Quantity', 'wooshop' ); ?>
    </label>

    <input
        type="number"
        class="form-control qty"
        name="<?php echo esc_attr( $input_name ); ?>"
        id="<?php echo esc_attr( $input_name ); ?>"
        value="<?php echo esc_attr( $input_value ); ?>"
        min="<?php echo isset( $min_value ) ? esc_attr( $min_value ) : '1'; ?>"
        <?php
        if ( isset( $max_value ) && '' !== $max_value ) {
            printf(
                ' max="%s"',
                esc_attr( $max_value )
            );
        }
        ?>
        step="<?php echo isset( $step ) ? esc_attr( $step ) : '1'; ?>"
    >
</div>