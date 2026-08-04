<?php
/**
 * Header Branding
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description' );
?>

<div class="ws-branding">

    <a
            class="ws-branding__link"
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            rel="home"
            aria-label="<?php echo esc_attr( sprintf( __( 'Go to %s homepage', 'wooshop' ), $site_name ) ); ?>"
    >

        <?php if ( has_custom_logo() ) : ?>

            <div class="ws-branding__logo">
                <?php the_custom_logo(); ?>
            </div>

        <?php else : ?>

            <div class="ws-branding__text">

                <span class="ws-branding__title">

                    <?php echo esc_html( $site_name ); ?>

                </span>

                <?php if ( ! empty( $tagline ) ) : ?>

                    <span class="ws-branding__tagline">

                        <?php echo esc_html( $tagline ); ?>

                    </span>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </a>

</div>