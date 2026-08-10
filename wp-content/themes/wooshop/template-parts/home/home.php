<?php
/**
 * WooShop Homepage
 *
 * Homepage presentation layer.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<main
        id="primary"
        class="site-main ws-homepage">

    <?php
    get_template_part(
            'template-parts/home/hero'
    );
    ?>

    <?php
    get_template_part(
            'template-parts/home/categories'
    );
    ?>

    <?php
    get_template_part(
            'template-parts/home/featured-products'
    );
    ?>

    <?php
    get_template_part(
            'template-parts/home/promo'
    );
    ?>

    <?php
    get_template_part(
            'template-parts/home/latest-products'
    );
    ?>

</main>