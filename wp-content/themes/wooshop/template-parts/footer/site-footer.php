<?php
/**
 * Site Footer.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container py-5">

    <div class="row g-4">

        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>

            <div class="col-12 col-md-6 col-lg-4">
                <?php dynamic_sidebar( 'footer-1' ); ?>
            </div>

        <?php endif; ?>

        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>

            <div class="col-12 col-md-6 col-lg-4">
                <?php dynamic_sidebar( 'footer-2' ); ?>
            </div>

        <?php endif; ?>

        <div class="col-12 col-lg-4">
            <nav
                class="footer-navigation"
                aria-label="<?php esc_attr_e( 'Footer Menu', 'wooshop' ); ?>"
            >

                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'menu_class'     => 'list-unstyled mb-0',
                        'container'      => false,
                        'fallback_cb'    => false,
                    )
                );
                ?>

            </nav>
        </div>

    </div>

    <div class="border-top mt-4 pt-4">

        <p class="small text-body-secondary mb-0">
            <?php
            printf(
            /* translators: %s: Site name. */
                esc_html__( '© %s', 'wooshop' ),
                esc_html( get_bloginfo( 'name' ) )
            );
            ?>
        </p>

    </div>

</div>