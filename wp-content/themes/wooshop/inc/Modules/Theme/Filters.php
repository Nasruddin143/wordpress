<?php
/**
 * Theme Filters Module.
 *
 * Handles general WordPress filters required by WooShop.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme filters module.
 */
final class Filters extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'body_class',
            array( $this, 'body_classes' )
        );

        add_filter(
            'excerpt_length',
            array( $this, 'excerpt_length' )
        );

        add_filter(
            'excerpt_more',
            array( $this, 'excerpt_more' )
        );
    }

    /**
     * Add WooShop body classes.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function body_classes( array $classes ): array {

        $classes[] = 'ws-theme';

        if ( is_front_page() ) {
            $classes[] = 'ws-front-page';
        }

        if ( is_home() ) {
            $classes[] = 'ws-home';
        }

        if ( is_singular() ) {
            $classes[] = 'ws-singular';
        }

        if ( is_archive() ) {
            $classes[] = 'ws-archive';
        }

        if ( is_search() ) {
            $classes[] = 'ws-search';
        }

        if ( is_404() ) {
            $classes[] = 'ws-404';
        }

        return array_values( array_unique( $classes ) );
    }

    /**
     * Change default excerpt length.
     *
     * @param int $length Excerpt length.
     *
     * @return int
     */
    public function excerpt_length( int $length ): int {
        return 25;
    }

    /**
     * Change excerpt ending.
     *
     * @param string $more Excerpt ending.
     *
     * @return string
     */
    public function excerpt_more( string $more ): string {
        return '&hellip;';
    }
}