<?php
/**
 * WooShop Theme Assets Module
 *
 * Loads global theme styles and scripts through the
 * centralized AssetsManager.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined("ABSPATH") || exit();

/**
 * Class Assets
 *
 * Handles global WooShop theme assets.
 */
class Assets extends Module
{
    /**
     * Assets manager.
     *
     * @var AssetsManager
     */
    protected mixed $assets;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->assets = $container->get(AssetsManager::class);
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action("wp_enqueue_scripts", [$this, "enqueue"], 10);
    }

    /**
     * Enqueue global theme assets.
     *
     * Bootstrap is loaded first, followed by the
     * WooShop application CSS and JavaScript.
     *
     * @return void
     */
    public function enqueue(): void
    {
        /*
         * Global styles.
         */
        $this->assets->enqueue_style("bootstrap");

        $this->assets->enqueue_style("app");

        /*
         * Global scripts.
         */
        $this->assets->enqueue_script("bootstrap");

        $this->assets->enqueue_script("app");
    }
}
