<?php
/**
 * Sidebar
 *
 * Displays the primary sidebar widget area.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-sidebar-inner">

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>

        <?php dynamic_sidebar( 'sidebar-1' ); ?>

    <?php endif; ?>

</div>