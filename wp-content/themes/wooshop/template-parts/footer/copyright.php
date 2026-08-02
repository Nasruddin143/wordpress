<?php
/**
 * Footer Copyright
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="site-info">

    <p>

        &copy;
        <?php echo esc_html( gmdate( 'Y' ) ); ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php bloginfo( 'name' ); ?>
        </a>

    </p>

</div>