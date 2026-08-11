<?php
/**
 * Smart Asset Loader.
 *
 * Determines which asset bundles should be registered
 * for the current WordPress request.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class AssetLoader
{

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Asset manager.
     *
     * @var AssetManager
     */
    protected AssetManager $assets;

    /**
     * Configuration repository.
     *
     * @var Config
     */
    protected Config $config;

    /**
     * Condition resolver.
     *
     * @var Condition
     */
    protected Condition $condition;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container )
    {
        $this->container = $container;

        $this->assets = $container->get(
            AssetManager::class
        );

        $this->config = $container->get(
            Config::class
        );

        $this->condition = $container->get(
            Condition::class
        );
    }

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'wp_enqueue_scripts',
            [ $this, 'load' ],
            10
        );
    }

    /**
     * Load contextual assets.
     *
     * @return void
     */
    public function load(): void
    {
        $contexts = $this->condition->assetContexts();

        foreach ( $contexts as $context ) {

            $this->registerContext(
                $context
            );
        }
    }

    /**
     * Register assets for a context.
     *
     * @param string $context Asset context.
     * @return void
     */
    protected function registerContext( string $context ): void
    {
        $config = $this->config->get(
            'assets',
            []
        );

        if ( empty( $config[ $context ] ) ) {
            return;
        }

        $contextConfig = $config[ $context ];

        /*
         * Register styles.
         */
        foreach (
            $contextConfig['styles'] ?? []
            as $style
        ) {

            if ( empty( $style['handle'] ) || empty( $style['src'] ) ) {
                continue;
            }

            $this->assets->registerStyle(
                $style['handle'],
                $style['src'],
                $style['deps'] ?? [],
                $style['media'] ?? 'all'
            );
        }

        /*
         * Register scripts.
         */
        foreach (
            $contextConfig['scripts'] ?? []
            as $script
        ) {

            if ( empty( $script['handle'] ) || empty( $script['src'] ) ) {
                continue;
            }

            $this->assets->registerScript(
                $script['handle'],
                $script['src'],
                $script['deps'] ?? [],
                $script['strategy'] ?? 'defer',
                $script['footer'] ?? true
            );
        }
    }
}