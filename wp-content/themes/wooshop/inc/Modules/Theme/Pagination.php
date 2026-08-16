<?php
/**
 * WordPress Pagination.
 *
 * Provides reusable pagination functionality for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress pagination functionality.
 */
class Pagination extends Module {

    /**
     * Register the module.
     *
     * @return void
     */
    public function register(): void {
        // Pagination does not require global hooks.
    }

    /**
     * Render post pagination.
     *
     * @return void
     */
    public function render(): void {

        the_posts_pagination(
            [
                'mid_size'           => 1,
                'prev_text'          => esc_html__( 'Previous', 'wooshop' ),
                'next_text'          => esc_html__( 'Next', 'wooshop' ),
                'screen_reader_text' => esc_html__( 'Posts navigation', 'wooshop' ),
                'class'              => 'pagination justify-content-center',
            ]
        );
    }
}