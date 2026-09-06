<?php
/**
 * WooShop Empty Shop
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="shop-empty text-center py-5">

    <div class="shop-empty__icon mb-4" aria-hidden="true">
        <i class="bi bi-box-seam"></i>
    </div>

    <h2 class="h4 mb-3">

        <?php esc_html_e('No products found', 'wooshop'); ?>

    </h2>

    <p class="text-muted mb-4">

        <?php esc_html_e('We could not find any products matching your selection.', 'wooshop'); ?>

    </p>

    <?php if (wc_get_page_id('shop') > 0) : ?>

        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">

            <?php esc_html_e('Browse Products', 'wooshop'); ?>

        </a>

    <?php endif; ?>

</div>