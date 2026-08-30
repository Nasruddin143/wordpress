<?php
/**
 * Wishlist Button.
 *
 * @package WooShop
 */

use WooShop\Modules\WooCommerce\Wishlist;

defined('ABSPATH') || exit;

global $product;

if (!$product instanceof WC_Product) {
    return;
}

$product_id = $product->get_id();

/*
 * Determine wishlist state.
 */
$wishlist_module = apply_filters('wooshop_wishlist_module', null);

$is_wishlisted = false;

if ($wishlist_module instanceof Wishlist) {
    $is_wishlisted = $wishlist_module->has_item($product_id);
}
?>

<button
        type="button"
        class="btn btn-outline-secondary wishlist-button"
        data-wishlist-button
        data-product-id="<?php echo esc_attr($product_id); ?>"
        aria-pressed="<?php echo $is_wishlisted ? 'true' : 'false'; ?>"

>

    ```
    <span
            class="wishlist-button__icon"
            aria-hidden="true"
    >
    ♡
</span>

    <span class="wishlist-button__text">

    <?php echo esc_html($is_wishlisted ? __('Remove from wishlist', 'wooshop') : __('Add to wishlist', 'wooshop')); ?>

</span>
    ```

</button>
