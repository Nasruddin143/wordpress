<?php
/**
 * WooShop Shop Header
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<header class="shop-header mb-4">

    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>

        <h1 class="shop-header__title h2 mb-3 fw-bold">

            <?php woocommerce_page_title(); ?>

        </h1>

    <?php endif; ?>

    <?php do_action('woocommerce_archive_description'); ?>

</header>