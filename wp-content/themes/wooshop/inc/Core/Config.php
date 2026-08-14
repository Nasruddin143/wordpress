<?php
/**
 * Configuration Repository
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Config
{
    /**
     * Loaded configuration.
     *
     * @var array<string,mixed>
     */
    protected array $items = [];

    /**
     * Theme config path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Constructor.
     */
    public function __construct( string $path )
    {
        $this->path = untrailingslashit( $path );
    }

    /**
     * Get configuration.
     */
    public function get( string $file, $default = [] )
    {
        if ( isset( $this->items[ $file ] ) ) {
            return $this->items[ $file ];
        }

        $config = $this->path . '/' . $file . '.php';

        if ( ! file_exists( $config ) ) {
            return $default;
        }

        $this->items[ $file ] = require $config;

        return $this->items[ $file ];
    }

    /**
     * Check config exists.
     */
    public function has( string $file ): bool
    {
        return file_exists(
            $this->path . '/' . $file . '.php'
        );
    }

    /**
     * Forget cached config.
     */
    public function forget( string $file ): void
    {
        unset( $this->items[ $file ] );
    }

    /**
     * Clear all cached config.
     */
    public function flush(): void
    {
        $this->items = [];
    }
}