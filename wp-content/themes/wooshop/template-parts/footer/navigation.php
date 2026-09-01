<?php
/**
 * WooShop Footer Navigation
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!has_nav_menu('footer')) {
    return;
}
?>

<nav
        class="footer-navigation py-4"
        aria-label="<?php esc_attr_e('Footer Navigation', 'wooshop'); ?>"
>

    <?php
    wp_nav_menu(
            [
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'nav justify-content-center',
                    'menu_id' => 'footer-menu',
                    'fallback_cb' => false,
                    'depth' => 1,
            ]
    );
    ?>

</nav>
