<?php
/**
 * Footer Widgets
 *
 * Displays registered footer widget areas.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-footer-widgets">

    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>

        <?php dynamic_sidebar( 'footer-1' ); ?>

    <?php else : ?>

        <h5 class="ws-footer-title">
            <?php esc_html_e( 'WooShop', 'wooshop' ); ?>
        </h5>

        <p class="mb-0">
            <?php
            esc_html_e(
                'Quality products with a simple shopping experience.',
                'wooshop'
            );
            ?>
        </p>

    <?php endif; ?>

</div>