<?php
/**
 * Theme Assets
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

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

];