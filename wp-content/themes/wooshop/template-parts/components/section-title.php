<?php
/**
 * Section Title Component
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$title = $args['title'] ?? '';

if ( '' === $title ) {
    return;
}
?>

<header class="section-header">

    <h2 class="section-title">

        <?php echo esc_html( $title ); ?>

    </h2>

</header>