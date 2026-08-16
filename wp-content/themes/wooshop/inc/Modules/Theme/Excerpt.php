<?php
/**
 * WordPress Excerpts.
 *
 * Handles excerpt-related functionality for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress excerpt functionality.
 */
class Excerpt extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'excerpt_length',
            [ $this, 'excerpt_length' ],
            20
        );

        add_filter(
            'excerpt_more',
            [ $this, 'excerpt_more' ]
        );
    }

    /**
     * Set the default excerpt length.
     *
     * @param int $length Excerpt word count.
     * @return int
     */
    public function excerpt_length( int $length ): int {

        return 25;
    }

    /**
     * Set the excerpt continuation text.
     *
     * @param string $more Excerpt continuation text.
     * @return string
     */
    public function excerpt_more( string $more ): string {

        return '&hellip;';
    }
}