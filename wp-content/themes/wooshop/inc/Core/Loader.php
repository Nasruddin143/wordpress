<?php
/**
 * Theme Loader
 *
 * @package WooShop
 */

namespace WooShop\Core;

use WooShop\Core\Icons\Icon;
use WooShop\Core\Icons\Manager;

defined('ABSPATH') || exit;

class Loader
{

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Boot framework.
     *
     * @return void
     */
    public function boot(): void
    {
        /*
        * Load global helper functions.
        */
        require_once get_template_directory() . '/inc/Helpers/loader.php';

        /*
         * Create service container.
         */
        $this->container = new Container();

        /*
         * Make container available to the application.
         */
        Application::set_container($this->container);

        /*
         * Register framework services.
         */
        $this->register_services();

        /*
         * Register WordPress hooks.
         */
        $this->register_hooks();

        /*
         * Load registered modules.
         */
        $this->load_modules();
    }

    /**
     * Register framework services.
     *
     * @return void
     */
    protected function register_services(): void
    {
        /*
        * ---------------------------------------------------------
        * Configuration Repository.
        * ---------------------------------------------------------
        */
        $this->container->set(
            Config::class,
            function () {

                return new Config(
                    get_template_directory() . '/inc/Config'
                );

            }
        );

        /*
         * ---------------------------------------------------------
         * Condition Resolver.
         * ---------------------------------------------------------
         */
        $this->container->set(
            Condition::class,
            function () {

                return new Condition();

            }
        );

        /*
        * ---------------------------------------------------------
        * Asset Manager.
        * ---------------------------------------------------------
        */
        $this->container->set(
            AssetManager::class,
            function () {

                return new AssetManager();

            }
        );
//        $this->container->set(
//            AssetManager::class,
//            function (Container $container) {
//
//                $assets = new AssetManager();
//
//                /** @var Config $config */
//                $config = $container->get(
//                    Config::class
//                );
//
//                $assetConfig = $config->get(
//                    'assets',
//                    []
//                );
//
//                /*
//                 * Register editor styles.
//                 */
//                foreach (
//                    $assetConfig['editor']['styles'] ?? []
//                    as $asset
//                ) {
//
//                    $assets->registerEditorStyle(
//                        $asset['handle'],
//                        $asset['src'],
//                        $asset['deps'] ?? []
//                    );
//                }
//
//                /*
//                 * Register editor scripts.
//                 */
//                foreach (
//                    $assetConfig['editor']['scripts'] ?? []
//                    as $asset
//                ) {
//
//                    $assets->registerEditorScript(
//                        $asset['handle'],
//                        $asset['src'],
//                        $asset['deps'] ?? []
//                    );
//                }
//
//                return $assets;
//            }
//        );

        /*
         * ---------------------------------------------------------
         * Smart Asset Loader.
         * ---------------------------------------------------------
         */
        $this->container->set(
            AssetLoader::class,
            function (Container $container) {

                return new AssetLoader(
                    $container
                );

            }
        );

        /*
         * ---------------------------------------------------------
         * Template Loader.
         * ---------------------------------------------------------
         */
        $this->container->set(
            TemplateLoader::class,
            function () {

                return new TemplateLoader();

            }
        );

        /*
         * ---------------------------------------------------------
         * View Renderer.
         * ---------------------------------------------------------
         */
        $this->container->set(
            View::class,
            function (Container $container) {

                return new View(

                    $container->get(
                        TemplateLoader::class
                    )
                );
            }
        );


        /*
         * ---------------------------------------------------------
         * Icon Manager.
         * ---------------------------------------------------------
         */
        $this->container->set(
            Manager::class,
            function () {

                return new Manager(
                    get_template_directory() . '/inc/Core/Icons/svg'
                );

            }
        );


        /*
        * ---------------------------------------------------------
        * Icon Renderer.
        * ---------------------------------------------------------
        */
        $this->container->set(
            Icon::class,
            function ($container) {

                return new Icon($container);

            }
        );
    }


    /**
     * Register WordPress hooks.
     *
     * Asset registration and enqueueing are intentionally
     * separated so smart asset detection happens before
     * assets are enqueued.
     *
     * @return void
     */
    protected function register_hooks(): void
    {
        /**
         * @var AssetLoader $assetLoader
         */
        $assetLoader = $this->container->get(
            AssetLoader::class
        );

        /**
         * @var AssetManager $assets
         */
        $assets = $this->container->get(
            AssetManager::class
        );

        /*
         * Smart asset registration.
         *
         * Priority 10:
         * Determine which assets are required
         * and register them with AssetManager.
         */
        $assetLoader->register();

        /*
         * Asset enqueue.
         *
         * Priority 20:
         * Enqueue everything registered above.
         */
        add_action(
            'wp_enqueue_scripts',
            [
                $assets,
                'enqueue',
            ],
            20
        );

        /*
         * Block editor assets.
         */
        add_action(
            'enqueue_block_editor_assets',
            [
                $assets,
                'enqueueEditor',
            ]
        );
    }

    /**
     * Load framework modules.
     *
     * @return void
     */
    protected
    function load_modules(): void
    {
        $manager = new ModuleManager(
            $this->container
        );

        $manager->load();
    }

    /**
     * Get service container.
     *
     * @return Container
     */
    public
    function get_container(): Container
    {

        return $this->container;
    }
}