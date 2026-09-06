<?php
/**
 * WooShop WooCommerce Sidebar.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!is_active_sidebar('woocommerce-sidebar')) {
    return;
}
?>

<aside id="woocommerce-sidebar" class="woocommerce-sidebar"
       aria-label="<?php echo esc_attr__('Shop Sidebar', 'wooshop'); ?>">

    <ul class="woocommerce-sidebar__widgets p-0">

        <?php dynamic_sidebar('woocommerce-sidebar'); ?>

    </ul>

</aside>