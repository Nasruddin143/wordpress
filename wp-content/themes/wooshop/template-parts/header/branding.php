<?php
/**
 * Header Branding
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-branding site-branding">

    <?php if ( has_custom_logo() ) : ?>

        <div class="custom-logo-link">
            <?php the_custom_logo(); ?>
        </div>

    <?php else : ?>

        <a
                class="ws-branding__link text-decoration-none"
                href="<?php echo esc_url( home_url( '/' ) ); ?>"
                rel="home">

            <span class="ws-branding__title fw-bold fs-4 text-dark">
                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
            </span>

        </a>

        <?php
        $description = get_bloginfo( 'description', 'display' );

        if ( $description ) :
            ?>

            <span class="ws-branding__description d-block small text-body-secondary">
                <?php echo esc_html( $description ); ?>
            </span>

        <?php endif; ?>

    <?php endif; ?>

</div>