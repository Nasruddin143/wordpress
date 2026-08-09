<?php
/**
 * Homepage Layout
 *
 * Main homepage presentation layer.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<main
    id="primary"
    class="site-main ws-home-main">

    <?php
    /**
     * Homepage content.
     *
     * Individual homepage sections will be attached here
     * as the WooShop homepage is developed.
     */
    do_action( 'wooshop_home' );
    ?>

</main>