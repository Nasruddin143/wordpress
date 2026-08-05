<?php
/**
 * Helper Loader
 *
 * @package WooShop
 */

use WooShop\Helpers\ProductCategories;

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
    'icons',
];

foreach ( $helper_files as $file ) {

    $path = __DIR__ . '/' . $file . '.php';

    if ( file_exists( $path ) ) {
        require_once $path;
    }
}

if ( ! function_exists( 'wooshop_product_category_dropdown' ) ) {

    function wooshop_product_category_dropdown(
        string $name = 'product_cat',
        string $selected = ''
    ): void {

        ProductCategories::dropdown(
            $name,
            $selected
        );

    }

}