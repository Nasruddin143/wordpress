<?php
/**
 * WooShop Configuration Manager
 *
 * Loads and provides access to static theme configuration files.
 *
 * Configuration is loaded from the inc/Config directory and kept
 * in memory for the current PHP request. No database queries are
 * performed by this class.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Config class.
 */
class Config {

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Loaded configuration values.
     *
     * @var array
     */
    protected array $config = array();

    /**
     * Configuration directory.
     *
     * @var string
     */
    protected string $config_path;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        $this->container   = $container;
        $this->config_path = get_stylesheet_directory() . '/inc/Config/';
    }

    /**
     * Get a configuration value.
     *
     * Supports dot notation, for example:
     * assets.styles.bootstrap
     *
     * @param string $key     Configuration key.
     * @param mixed  $default Default value.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null ): mixed
    {

        $segments = explode( '.', $key );

        $file = array_shift( $segments );

        if ( ! isset( $this->config[ $file ] ) ) {
            $this->load( $file );
        }

        if ( ! isset( $this->config[ $file ] ) ) {
            return $default;
        }

        $value = $this->config[ $file ];

        foreach ( $segments as $segment ) {

            if ( ! is_array( $value ) || ! array_key_exists( $segment, $value ) ) {
                return $default;
            }

            $value = $value[ $segment ];
        }

        return $value;
    }

    /**
     * Load one configuration file.
     *
     * The configuration file is loaded only once per request.
     *
     * @param string $file Configuration filename without .php.
     *
     * @return array
     */
    protected function load( string $file ): array {

        if ( isset( $this->config[ $file ] ) ) {
            return $this->config[ $file ];
        }

        $file = sanitize_file_name( $file );

        $path = $this->config_path . $file . '.php';

        if ( ! file_exists( $path ) ) {

            $this->config[ $file ] = array();

            return $this->config[ $file ];
        }

        $value = require $path;

        $this->config[ $file ] = is_array( $value )
            ? $value
            : array();

        return $this->config[ $file ];
    }

    /**
     * Determine whether a configuration file exists.
     *
     * @param string $file Configuration filename without .php.
     *
     * @return bool
     */
    public function has( string $file ): bool {

        $file = sanitize_file_name( $file );

        return file_exists(
            $this->config_path . $file . '.php'
        );
    }

    /**
     * Get the complete configuration file.
     *
     * @param string $file Configuration filename without .php.
     *
     * @return array
     */
    public function all( string $file ): array {

        return $this->load( $file );
    }
}