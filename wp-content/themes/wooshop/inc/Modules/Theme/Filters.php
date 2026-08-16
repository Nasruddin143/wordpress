<?php
/**
 * Theme Filters Module.
 *
 * Registers global WordPress filters used by the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles global WooShop theme filters.
 */
class Filters extends Module {

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        parent::__construct( $container );
    }

    /**
     * Register module filters.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'nav_menu_link_attributes',
            [ $this, 'navigation_link_attributes' ],
            10,
            4
        );
    }

    /**
     * Add accessibility attributes to navigation links.
     *
     * Only applies attributes when they are required.
     *
     * @param array    $atts     Navigation link attributes.
     * @param WP_Post  $item     Navigation menu item.
     * @param stdClass $args     Navigation menu arguments.
     * @param int      $depth    Menu depth.
     * @return array
     */
    public function navigation_link_attributes(
        array $atts,
        \WP_Post $item,
        \stdClass $args,
        int $depth
    ): array {

        if ( empty( $atts['href'] ) ) {
            $atts['aria-disabled'] = 'true';
        }

        return $atts;
    }
}