<?php
/**
 * Primary Navigation.
 *
 * @package WooShop
 */

use WooShop\Modules\Theme\BootstrapWalker;

defined( 'ABSPATH' ) || exit;
?>

<nav id="site-navigation" class="main-navigation navbar navbar-expand-lg bg-body-tertiary" aria-label="<?php esc_attr_e( 'Primary Menu', 'wooshop' ); ?>">

    <div class="container">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            wp_nav_menu(
                    array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu-list',
                            'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'walker'         => new BootstrapWalker(),
                    )
            );
            ?>
        </div>
    </div>
</nav>