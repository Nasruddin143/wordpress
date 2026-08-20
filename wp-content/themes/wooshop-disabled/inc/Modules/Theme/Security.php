<?php
/**
 * WooShop Security Module
 *
 * Provides lightweight theme-level security hardening without
 * interfering with WordPress core, WooCommerce, REST API,
 * authentication, or legitimate administrative functionality.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined('ABSPATH') || exit;

final class Security
{
    /**
     * Register the security module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'the_generator',
            [$this, 'remove_generator_metadata']
        );

        add_filter(
            'xmlrpc_enabled',
            [$this, 'disable_xmlrpc']
        );

        add_filter(
            'wp_headers',
            [$this, 'add_security_headers']
        );

        add_filter(
            'rest_pre_serve_request',
            [$this, 'add_rest_security_headers'],
            10,
            4
        );
    }

    /**
     * Remove the WordPress generator version.
     *
     * @param string $generator Generator metadata.
     *
     * @return string
     */
    public function remove_generator_metadata(
        string $generator
    ): string {
        return '';
    }

    /**
     * Disable XML-RPC.
     *
     * XML-RPC is not required by the WooShop theme itself.
     * Plugins that explicitly require XML-RPC should manage
     * this functionality independently.
     *
     * @param bool $enabled Whether XML-RPC is enabled.
     *
     * @return bool
     */
    public function disable_xmlrpc(bool $enabled): bool
    {
        return false;
    }

    /**
     * Add lightweight security-related HTTP headers.
     *
     * Headers are intentionally conservative so they do not
     * conflict with WooCommerce, payment gateways, embeds,
     * CDNs, or third-party integrations.
     *
     * @param array<string, string> $headers Existing headers.
     *
     * @return array<string, string>
     */
    public function add_security_headers(
        array $headers
    ): array {
        if (!is_admin()) {
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        }

        return $headers;
    }

    /**
     * Add security headers to REST API responses.
     *
     * @param bool             $served  Whether the request was served.
     * @param WP_REST_Response $result  REST response.
     * @param WP_REST_Request  $request REST request.
     * @param WP_REST_Server   $server  REST server.
     *
     * @return bool
     */
    public function add_rest_security_headers(
        bool            $served,
        mixed           $result,
        WP_REST_Request $request,
        WP_REST_Server $server
    ): bool {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');

        return $served;
    }
}