<?php
/**
 * Site Branding
 *
 * Displays the custom logo or site title and description.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-branding site-branding">

    <?php
    if ( has_custom_logo() ) {

        the_custom_logo();

    } else {
        ?>

        <a
            class="ws-site-title site-title fw-bold text-decoration-none"
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            rel="home"
        >
            <?php bloginfo( 'name' ); ?>
        </a>

        <?php
    }
    ?>

</div>