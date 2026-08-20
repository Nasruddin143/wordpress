<?php
/**
 * Footer Copyright
 *
 * Displays copyright and WordPress credit information.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-footer-copyright">

    <p class="mb-2">
        &copy;
        <?php echo esc_html( gmdate( 'Y' ) ); ?>
        <?php bloginfo( 'name' ); ?>
    </p>

    <p class="mb-0 small text-body-secondary">

        <?php
        printf(
        /* translators: %s: WordPress link. */
            esc_html__( 'Powered by %s', 'wooshop' ),
            '<a href="' . esc_url( __( 'https://wordpress.org/', 'wooshop' ) ) . '">WordPress</a>'
        );
        ?>

    </p>

</div>