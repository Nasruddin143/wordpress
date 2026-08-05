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

        Application::set_container( $this->container );

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
            function (Container $container) {

                $assets = new AssetManager();
                /** @var Config $config */
                $config = $container->get(Config::class);
                $asset_config = $config->get('assets', []);

                foreach (($asset_config['global']['styles'] ?? []) as $asset) {
                    $assets->registerStyle($asset['handle'], $asset['src'], $asset['deps'] ?? [], $asset['media'] ?? 'all');
                }
                foreach (($asset_config['global']['scripts'] ?? []) as $asset) {
                    $assets->registerScript($asset['handle'], $asset['src'], $asset['deps'] ?? [], $asset['strategy'] ?? 'defer', $asset['footer'] ?? true);
                }
                foreach (($asset_config['editor']['styles'] ?? []) as $asset) {
                    $assets->registerEditorStyle($asset['handle'], $asset['src'], $asset['deps'] ?? []);
                }
                foreach (($asset_config['editor']['scripts'] ?? []) as $asset) {
                    $assets->registerEditorScript($asset['handle'], $asset['src'], $asset['deps'] ?? []);
                }
                return $assets;
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
            function ( $container ) {

                return new Icon( $container );

            }
        );
    }

    /**
     * Register WordPress hooks.
     *
     * @return void
     */
    protected
    function register_hooks(): void
    {

        /**
         * @var AssetManager $assets
         */
        $assets = $this->container->get(
            AssetManager::class
        );

        add_action(
            'wp_enqueue_scripts',
            [
                $assets,
                'enqueue',
            ],
            20
        );

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