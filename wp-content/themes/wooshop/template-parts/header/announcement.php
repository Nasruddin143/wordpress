<?php
/**
 * Header Announcement Bar
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$message = apply_filters(
    'wooshop_header_announcement_message',
    __( '🚚 Free shipping on orders over ₹999', 'wooshop' )
);

$link = apply_filters(
    'wooshop_header_announcement_link',
    ''
);
?>

<?php if ( ! empty( $message ) ) : ?>

    <div class="ws-announcement">

        <div class="container">

            <?php if ( ! empty( $link ) ) : ?>

                <a
                    href="<?php echo esc_url( $link ); ?>"
                    class="ws-announcement__link"
                >

                    <?php echo wp_kses_post( $message ); ?>

                </a>

            <?php else : ?>

                <span class="ws-announcement__text">

                <?php echo wp_kses_post( $message ); ?>

            </span>

            <?php endif; ?>

        </div>

    </div>

<?php endif; ?>