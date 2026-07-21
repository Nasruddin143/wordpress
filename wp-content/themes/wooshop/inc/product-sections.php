<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get products for a homepage section
 *
 * @param array $args wc_get_products() arguments
 * @return array
 */
function wooshop_get_products($args = [])
{

    $defaults = [
        'status' => 'publish',
        'limit' => 10,
        'return' => 'objects'
    ];

    return wc_get_products(wp_parse_args($args, $defaults));
}