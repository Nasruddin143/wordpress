<?php
/**
 * Helper Loader
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$helper_files = [
    'helpers',
    'template-tags',
    'pagination',
    'breadcrumbs',
    'buttons',
    'entry',
    'post-meta',
    'formatting',
];

foreach ( $helper_files as $file ) {

    $path = __DIR__ . '/' . $file . '.php';

    if ( file_exists( $path ) ) {
        require_once $path;
    }
}