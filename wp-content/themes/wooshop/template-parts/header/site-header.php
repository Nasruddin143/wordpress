<?php
/**
 * Site Header.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<!--Topbar-->
<div class="topbar py-2 bg-light">
    <?php get_template_part('template-parts/header/topbar'); ?>
</div>

<!-- Main Header -->
<div class="header-container">

    <div class="container-fluid container-xl">

        <div class="d-flex align-items-center justify-content-between py-3">

            <?php get_template_part('template-parts/header/branding'); ?>

            <?php get_template_part('template-parts/header/search'); ?>

            <?php get_template_part('template-parts/header/navigation'); ?>

        </div>

    </div>

</div>

