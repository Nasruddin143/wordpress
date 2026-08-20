<?php
/**
 * Site Footer Template
 *
 * Provides the main WooShop footer structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<footer
    id="colophon"
    class="site-footer ws-footer border-top bg-light"
>

    <div class="container">

        <div class="row g-4 py-5">

            <div class="col-12 col-md-6 col-lg-4">

                <?php
                get_template_part(
                    'template-parts/footer/widgets'
                );
                ?>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <?php
                get_template_part(
                    'template-parts/footer/navigation'
                );
                ?>

            </div>

            <div class="col-12 col-lg-4">

                <?php
                get_template_part(
                    'template-parts/footer/copyright'
                );
                ?>

            </div>

        </div>

    </div>

</footer>