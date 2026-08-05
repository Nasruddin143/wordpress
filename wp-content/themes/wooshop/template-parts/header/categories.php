<?php
/**
 * Header Categories
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="ws-header-categories dropdown">

    <button
            class="btn btn-light ws-categories-toggle dropdown-toggle"
            type="button"
            id="wsCategoriesMenu"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="<?php esc_attr_e('Browse Categories', 'wooshop'); ?>">

        <span class="ws-categories-icon">
            <?php echo wooshop_icon( 'menu' ); ?>
        </span>

        <span class="ws-categories-text">

            <?php esc_html_e('Browse Categories', 'wooshop'); ?>

        </span>

    </button>

    <?php

    wp_nav_menu(
            [
                    'theme_location' => 'categories',
                    'container' => false,
                    'menu_class' => 'dropdown-menu ws-categories-menu',
                    'fallback_cb' => false,
                    'depth' => 2,
            ]
    );

    ?>

</div>