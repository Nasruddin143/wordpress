<?php
/**
 * WordPress Template.
 *
 * Handles global template-related functionality for WooShop.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress template functionality.
 */
class Template extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'body_class',
            [ $this, 'body_classes' ]
        );

    }

    /**
     * Add WooShop classes to the body element.
     *
     * @param array $classes Existing body classes.
     * @return array
     */
    public function body_classes( array $classes ): array {

        $classes[] = 'wooshop';

        if ( is_front_page() ) {
            $classes[] = 'wooshop-front-page';
        }

        if ( is_home() ) {
            $classes[] = 'wooshop-blog';
        }

        if ( is_page() ) {
            $classes[] = 'wooshop-page';
        }

        if ( is_single() ) {
            $classes[] = 'wooshop-single';
        }

        if ( is_archive() ) {
            $classes[] = 'wooshop-archive';
        }

        if ( is_search() ) {
            $classes[] = 'wooshop-search';
        }

        if ( is_404() ) {
            $classes[] = 'wooshop-404';
        }

        return $classes;
    }

}