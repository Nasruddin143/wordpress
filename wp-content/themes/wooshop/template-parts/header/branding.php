<?php
/**
 * Header Branding
 *
 * Displays the site logo and branding.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-branding">

    <?php if ( has_custom_logo() ) : ?>

        <?php the_custom_logo(); ?>

    <?php else : ?>

        <a
                class="ws-site-title"
                href="<?php echo esc_url( home_url( '/' ) ); ?>"
                rel="home">

            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>

        </a>

    <?php endif; ?>

</div>