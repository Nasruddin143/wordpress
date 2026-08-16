<?php
/**
 * Theme Performance Module.
 *
 * Handles lightweight WordPress performance optimizations
 * used globally by the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop performance functionality.
 */
class Performance extends Module {

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
            'wp_resource_hints',
            [ $this, 'resource_hints' ],
            10,
            2
        );

        add_filter(
            'wp_lazy_loading_enabled',
            [ $this, 'lazy_loading' ],
            10,
            3
        );
    }

    /**
     * Add resource hints.
     *
     * Only explicitly required external resources should be added here.
     *
     * @param array  $urls          Resource hint URLs.
     * @param string $relation_type Resource hint relation.
     * @return array
     */
    public function resource_hints(
        array $urls,
        string $relation_type
    ): array {

        return $urls;
    }

    /**
     * Control WordPress lazy loading.
     *
     * Keep WordPress native lazy loading enabled.
     *
     * @param bool   $default     Whether lazy loading is enabled.
     * @param string $tag_name    HTML element name.
     * @param string $context     Context where the element is used.
     * @return bool
     */
    public function lazy_loading(
        bool $default,
        string $tag_name,
        string $context
    ): bool {

        return $default;
    }
}