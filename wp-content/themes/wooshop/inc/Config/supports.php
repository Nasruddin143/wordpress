<?php
/**
 * Theme Supports Configuration
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

    'automatic-feed-links',

    'title-tag',

    'post-thumbnails',

    'responsive-embeds',

    'align-wide',

    'wp-block-styles',

    'editor-styles',

    'appearance-tools',

    'custom-line-height',

    'custom-spacing',

    [
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ],
    ],

    [
        'custom-logo',
        [
            'height'      => 120,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ],
    ],

];