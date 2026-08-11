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
        require_once get_template_directory() . '/inc/Helpers/loader.php';

        $this->container = new Container();

        Application::set_container($this->container);

        $this->register_services();

        $this->register_hooks();

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
         * Configuration Repository.
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
         * Condition Resolver.
         */
        $this->container->set(
            Condition::class,
            function () {

                return new Condition();

            }
        );

        /*
         * Asset Manager.
         */
        $this->container->set(
            AssetManager::class,
            function () {

                return new AssetManager();

            }
        );

        /*
         * Smart Asset Loader.
         */
        $this->container->set(
            AssetLoader::class,
            function ( Container $container ) {

                return new AssetLoader(
                    $container
                );

            }
        );

        /*
         * Template Loader.
         */
        $this->container->set(
            TemplateLoader::class,
            function () {

                return new TemplateLoader();

            }
        );

        /*
         * View Renderer.
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

        $this->container->set(
            Manager::class,
            function () {

                return new Manager(
                    get_template_directory() . '/inc/Core/Icons/svg'
                );

            }
        );

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