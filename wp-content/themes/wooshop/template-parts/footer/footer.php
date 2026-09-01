<?php
/**
 * WooShop Footer Template
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<?php
/**
 * WooShop Footer Template Part
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$config = $args['config'] ?? [];

if (!is_array($config)) {
    $config = [];
}
?>

    <footer id="colophon" class="site-footer" role="contentinfo">

        <div class="site-footer__main py-5">

            <div class="container">

                <?php
                get_template_part(
                        'template-parts/footer/widgets',
                        null,
                        [
                                'config' => $config,
                        ]
                );
                ?>

            </div>

        </div>

        <div class="site-footer__navigation border-top">

            <div class="container">

                <?php
                get_template_part(
                        'template-parts/footer/navigation',
                        null,
                        [
                                'config' => $config,
                        ]
                );
                ?>

            </div>

        </div>

        <div class="site-footer__bottom border-top">

            <div class="container">

                <?php
                get_template_part(
                        'template-parts/footer/bottom',
                        null,
                        [
                                'config' => $config,
                        ]
                );
                ?>

            </div>

        </div>

    </footer>


<?php get_template_part('template-parts/footer/back-to-top'); ?>