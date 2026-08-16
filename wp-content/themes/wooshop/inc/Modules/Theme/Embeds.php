<?php
/**
 * Theme Embeds Module.
 *
 * Handles WordPress embed-related theme functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress embeds.
 */
class Embeds extends Module {

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
            'embed_defaults',
            [ $this, 'embed_defaults' ]
        );
    }

    /**
     * Configure default embed dimensions.
     *
     * The responsive embed wrapper remains controlled by WordPress.
     *
     * @param array $args Embed arguments.
     * @return array
     */
    public function embed_defaults( array $args ): array {

        $args['width'] = 1200;

        $args['height'] = 675;

        return $args;
    }
}