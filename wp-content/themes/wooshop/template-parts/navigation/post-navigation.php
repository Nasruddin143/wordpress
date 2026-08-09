<?php
/**
 * Single Post Navigation
 *
 * Displays previous and next post navigation.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$previous_post = get_previous_post();
$next_post     = get_next_post();

if ( ! $previous_post && ! $next_post ) {
    return;
}
?>

<nav
    class="ws-post-navigation"
    aria-label="<?php esc_attr_e( 'Post navigation', 'wooshop' ); ?>">

    <?php if ( $previous_post ) : ?>

        <div class="ws-post-navigation__previous">

            <span class="ws-post-navigation__label">
                <?php
                esc_html_e(
                    'Previous Post',
                    'wooshop'
                );
                ?>
            </span>

            <a
                class="ws-post-navigation__link"
                href="<?php echo esc_url( get_permalink( $previous_post ) ); ?>">

                <span
                    class="ws-post-navigation__arrow"
                    aria-hidden="true">
                    &larr;
                </span>

                <span class="ws-post-navigation__title">

                    <?php
                    echo esc_html(
                        get_the_title( $previous_post )
                    );
                    ?>

                </span>

            </a>

        </div>

    <?php endif; ?>


    <?php if ( $next_post ) : ?>

        <div class="ws-post-navigation__next">

            <span class="ws-post-navigation__label">
                <?php
                esc_html_e(
                    'Next Post',
                    'wooshop'
                );
                ?>
            </span>

            <a
                class="ws-post-navigation__link"
                href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">

                <span class="ws-post-navigation__title">

                    <?php
                    echo esc_html(
                        get_the_title( $next_post )
                    );
                    ?>

                </span>

                <span
                    class="ws-post-navigation__arrow"
                    aria-hidden="true">
                    &rarr;
                </span>

            </a>

        </div>

    <?php endif; ?>

</nav>