<?php
/**
 * Footer Widgets
 *
 * Displays footer widget areas.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-footer-widgets">

    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>

        <?php $sidebar = 'footer-' . $i; ?>

        <?php if ( is_active_sidebar( $sidebar ) ) : ?>

            <div class="ws-footer-column">

                <?php dynamic_sidebar( $sidebar ); ?>

            </div>

        <?php endif; ?>

    <?php endfor; ?>

</div>