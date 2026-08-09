<?php
/**
 * WooCommerce Template Wrapper
 *
 * Provides the main theme wrapper for WooCommerce pages.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main
        id="primary"
        class="site-main ws-woocommerce-main">

        <div class="ws-container">

            <?php
            woocommerce_content();
            ?>

        </div>

    </main>

<?php
get_footer();