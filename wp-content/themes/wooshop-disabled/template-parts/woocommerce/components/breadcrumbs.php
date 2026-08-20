<?php
/**
 * WooCommerce Breadcrumbs
 *
 * Provides the WooShop WooCommerce breadcrumb wrapper.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
    return;
}
?>

<nav
    class="ws-woocommerce-breadcrumbs"
    aria-label="<?php esc_attr_e( 'Breadcrumb', 'wooshop' ); ?>"
>

    <?php
    woocommerce_breadcrumb(
        array(
            'delimiter'   => '<span class="breadcrumb-item-separator" aria-hidden="true">/</span>',
            'wrap_before' => '<ol class="breadcrumb mb-0">',
            'wrap_after'  => '</ol>',
            'before'      => '<li class="breadcrumb-item">',
            'after'       => '</li>',
        )
    );
    ?>

</nav>