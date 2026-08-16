<?php
/**
 * Breadcrumbs Component
 *
 * Displays a lightweight breadcrumb trail.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
    class="ws-breadcrumbs"
    aria-label="<?php esc_attr_e( 'Breadcrumb', 'wooshop' ); ?>"
>

    <ol class="breadcrumb mb-0">

        <li class="breadcrumb-item">

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php esc_html_e( 'Home', 'wooshop' ); ?>
            </a>

        </li>

        <?php if ( is_page() && ! is_front_page() ) : ?>

            <li
                class="breadcrumb-item active"
                aria-current="page"
            >
                <?php the_title(); ?>
            </li>

        <?php elseif ( is_single() ) : ?>

            <li
                class="breadcrumb-item active"
                aria-current="page"
            >
                <?php the_title(); ?>
            </li>

        <?php elseif ( is_archive() ) : ?>

            <li
                class="breadcrumb-item active"
                aria-current="page"
            >
                <?php the_archive_title(); ?>
            </li>

        <?php endif; ?>

    </ol>

</nav>