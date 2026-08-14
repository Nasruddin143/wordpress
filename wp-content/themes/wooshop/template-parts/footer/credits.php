<?php
/**
 * Footer Credits
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="footer-credits">

    <?php

    /**
     * Allow modules or child themes
     * to output footer credits.
     */
    do_action( 'wooshop_footer_credits' );

    ?>

</div>