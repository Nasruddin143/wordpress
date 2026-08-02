<?php
/**
 * Button Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_button' ) ) {

    function wooshop_button(
        string $text,
        string $url,
        string $class = 'button'
    ): void {
        ?>

        <a
            href="<?php echo esc_url( $url ); ?>"
            class="<?php echo esc_attr( $class ); ?>"
        >

            <?php echo esc_html( $text ); ?>

        </a>

        <?php
    }
}