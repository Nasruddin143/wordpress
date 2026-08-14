<?php
/**
 * WooShop Smart Asset Loader.
 *
 * Determines which frontend asset bundles should be
 * registered for the current request.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class AssetLoader
{

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
     * Register WordPress hook.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'wp_enqueue_scripts',
            [
                $this,
                'load',
            ],
            10
        );
    }

    /**
     * Load contextual frontend assets.
     *
     * @return void
     */
    public function load(): void
    {
        $contexts = $this->condition->assetContexts();

        foreach ( $contexts as $context ) {

            $this->loadContext(
                $context
            );
        }
    }

    /**
     * Load one asset context.
     *
     * @param string $context Asset context.
     *
     * @return void
     */
    protected function loadContext( string $context ): void
    {
        $assets = $this->config->get(
            'assets',
            []
        );

        if ( empty( $assets[ $context ] ) ) {
            return;
        }

        $contextAssets = $assets[ $context ];

        /*
         * Styles.
         */
        foreach (
            $contextAssets['styles'] ?? []
            as $style
        ) {

            if (
                empty( $style['handle'] )
                || empty( $style['src'] )
            ) {
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
         * Scripts.
         */
        foreach (
            $contextAssets['scripts'] ?? []
            as $script
        ) {

            if (
                empty( $script['handle'] )
                || empty( $script['src'] )
            ) {
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