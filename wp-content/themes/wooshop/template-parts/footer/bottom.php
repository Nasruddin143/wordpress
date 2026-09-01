<?php
/**
 * WooShop Footer Bottom
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$config = $args['config'] ?? [];

if (!is_array($config)) {
    $config = [];
}

$site_name = get_bloginfo('name');

$current_year = wp_date('Y');
?>

<div class="footer-bottom py-4">

    <div class="row align-items-center g-3">

        <div class="col-12 col-md">

            <p class="mb-0 text-center text-md-start">

                <?php printf(esc_html__('© %1$s %2$s. All rights reserved.', 'wooshop'), esc_html($current_year), esc_html($site_name)); ?>

            </p>

        </div>

        <div class="col-12 col-md-auto">

            <div class="text-center text-md-end">

                <?php esc_html_e('Powered by WordPress', 'wooshop'); ?>

            </div>

        </div>

    </div>

</div>
