<?php
/**
 * WooShop Shop Breadcrumb
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!function_exists('woocommerce_breadcrumb')) {
    return;
}
?>

<div class="shop-breadcrumb mb-4">

    <?php
    woocommerce_breadcrumb(
            [
                    'delimiter' => ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" class="main-grid-item-icon"
                                         fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg> ',
                    'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'wooshop') . '">',
                    'wrap_after' => '</nav>',
            ]
    );
    ?>

</div>


