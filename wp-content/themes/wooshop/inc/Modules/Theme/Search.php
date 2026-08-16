<?php
/**
 * WordPress Search.
 *
 * Provides reusable search functionality for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress search functionality.
 */
class Search extends Module {

    /**
     * Register the module.
     *
     * @return void
     */
    public function register(): void {
        // Search uses native WordPress functionality.
    }

    /**
     * Render the WordPress search form.
     *
     * @param bool $echo Whether to output the form.
     * @return string|void
     */
    public function render( bool $echo = true ) {

        $form = get_search_form(
            [
                'echo' => false,
            ]
        );

        if ( $echo ) {
            echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }

        return $form;
    }
}