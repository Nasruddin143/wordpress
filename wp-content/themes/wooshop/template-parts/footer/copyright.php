<?php
/**
 * Footer Copyright
 *
 * Displays the copyright information.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-footer-copyright">

    <p>
        &copy;
        <?php echo esc_html( wp_date( 'Y' ) ); ?>

        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.

        <?php esc_html_e( 'All rights reserved.', 'wooshop' ); ?>

    </p>

</div>