<?php
/**
 * Archive Header
 *
 * Displays archive title and description.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header class="ws-archive-header mb-5">

    <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>

    <?php the_archive_description( '<div class="archive-description text-body-secondary">', '</div>' ); ?>

</header>