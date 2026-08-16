<?php
/**
 * Theme Security Module.
 *
 * Handles lightweight WordPress security-related theme functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop theme security functionality.
 */
class Security extends Module {

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        parent::__construct( $container );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'the_generator',
            [ $this, 'remove_generator' ]
        );
    }

    /**
     * Remove the WordPress generator version.
     *
     * Prevents the WordPress version from being exposed
     * through the generator meta output.
     *
     * @param string $generator Generator output.
     * @return string
     */
    public function remove_generator( string $generator ): string {

        return '';
    }
}