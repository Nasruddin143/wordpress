<?php
/**
 * Theme Assets
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

    'global' => [

        'styles' => [

            [
                'handle' => 'bootstrap',
                'src'    => 'assets/vendor/bootstrap/css/bootstrap.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

            [
                'handle' => 'wooshop',
                'src'    => 'assets/build/css/app.min.css',
                'deps'   => ['bootstrap'],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle' => 'bootstrap',
                'src'     => 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'deps'    => [],
                'footer'  => true,
            ],

            [
                'handle' => 'wooshop',
                'src'     => 'assets/build/js/app.min.js',
                'deps'    => ['bootstrap'],
                'footer'  => true,
            ],

        ],

    ],

    'editor' => [

        'styles' => [

            [
                'handle' => 'wooshop-editor',
                'src'    => 'assets/build/css/editor.min.css',
            ],

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    */

    'styles' => [

        'app' => [

            'src' => 'assets/css/app.css',

            'deps' => [],

            'version' => null,

            'condition' => 'global',

        ],

        'bootstrap' => [

            'src' => 'assets/vendor/bootstrap/css/bootstrap.min.css',

            'deps' => [],

            'version' => '5.3.8',

            'condition' => 'global',

        ],

        'embla' => [

            'src' => 'assets/vendor/embla/embla.css',

            'deps' => [],

            'version' => null,

            'condition' => 'slider',

        ],

        'editor' => [

            'src' => 'assets/css/editor.css',

            'deps' => [],

            'version' => null,

            'condition' => 'editor',

        ],

        'sticky-header' => [
            'handle' => 'wooshop-sticky-header',
            'src'    => 'assets/build/css/components/sticky-header.css',
            'deps'   => ['wooshop'],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    */

    'scripts' => [

        'app' => [

            'src' => 'assets/js/app.js',

            'deps' => [],

            'footer' => true,

            'version' => null,

            'condition' => 'global',

        ],

        'bootstrap' => [

            'src' => 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js',

            'deps' => [],

            'footer' => true,

            'version' => '5.3.8',

            'condition' => 'global',

        ],

        'embla' => [

            'src' => 'assets/vendor/embla/embla.min.js',

            'deps' => [],

            'footer' => true,

            'version' => null,

            'condition' => 'slider',

        ],

        'navigation' => [

            'src' => 'assets/js/navigation.js',

            'deps' => [ 'app' ],

            'footer' => true,

            'version' => null,

            'condition' => 'global',

        ],

    ],

    'sticky-header' => [
        'handle' => 'wooshop-sticky-header',
        'src'    => 'assets/build/js/components/sticky-header.js',
        'deps'   => ['wooshop'],
    ],

];