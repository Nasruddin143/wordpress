<?php
/**
 * Header Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
    class="navbar navbar-expand-lg ws-navbar"
    aria-label="<?php esc_attr_e( 'Primary Navigation', 'wooshop' ); ?>"
>

    <div class="container">

        <?php get_template_part(
            'template-parts/header/navigation',
            'primary'
        ); ?>

    </div>

</nav>