<?php
/**
 * Header Navigation
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<nav id="site-navigation"
     class="ws-navigation navbar navbar-expand-lg"
     aria-label="<?php esc_attr_e('Primary Navigation', 'wooshop'); ?>">

    <div class="container">

        <button class="navbar-toggler ws-navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#wsPrimaryMenu"
                aria-controls="wsPrimaryMenu"
                aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle navigation', 'wooshop'); ?>">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="wsPrimaryMenu">

            <?php

            wp_nav_menu(
                    [
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'navbar-nav ws-navbar',
                            'fallback_cb' => false,
                            'depth' => 2,
                    ]
            );

            ?>

        </div>

    </div>

</nav>