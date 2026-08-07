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
                'deps'   => [ 'bootstrap' ],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'bootstrap',
                'src'      => 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'deps'     => [],
                'footer'   => true,
                'strategy' => 'defer',
            ],

            [
                'handle'   => 'wooshop',
                'src'      => 'assets/build/js/app.min.js',
                'deps'     => [ 'bootstrap' ],
                'footer'   => true,
                'strategy' => 'defer',
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

        'scripts' => [

            [
                'handle' => 'wooshop-editor',
                'src'    => 'assets/build/js/editor.min.js',
            ],

        ],

    ],

];