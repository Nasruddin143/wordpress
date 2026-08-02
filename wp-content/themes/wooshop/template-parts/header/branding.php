<?php
/**
 * Header Branding
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="site-branding">

    <?php

    if ( has_custom_logo() ) {

        the_custom_logo();

    } else {

        ?>

        <a
            class="site-title"
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            rel="home"
        >

            <?php bloginfo( 'name' ); ?>

        </a>

        <?php

        $description = get_bloginfo(
            'description',
            'display'
        );

        if ( $description ) :

            ?>

            <p class="site-description">

                <?php echo esc_html( $description ); ?>

            </p>

        <?php

        endif;

    }

    ?>

</div>