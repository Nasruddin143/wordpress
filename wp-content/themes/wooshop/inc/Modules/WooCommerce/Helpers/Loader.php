<?php
/**
 * WooCommerce Helper Loader
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$helpers = [

    'Product.php',

    'Price.php',

    'Rating.php',

    'Stock.php',

    'Sale.php',

];

foreach ( $helpers as $helper ) {

    $file = __DIR__ . '/' . $helper;

    if ( is_readable( $file ) ) {
        require_once $file;
    }
}