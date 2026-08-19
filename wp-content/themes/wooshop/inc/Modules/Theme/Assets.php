<?php
/**
 * WooShop Theme Assets Module
 *
 * Connects the WooShop Theme module system with the centralized
 * AssetsManager responsible for conditional asset loading.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WooShop\Core\AssetsManager;
use WooShop\Core\Config;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Assets
 *
 * Registers the WooShop Smart Asset Loading system.
 */
final class Assets extends Module {

    /**
     * WooShop assets manager.
     *
     * @var AssetsManager
     */
    private readonly AssetsManager $assets_manager;

    /**
     * Constructor.
     *
     * @param Container     $container     WooShop service container.
     * @param Config        $config        WooShop configuration manager.
     * @param AssetsManager $assets_manager WooShop assets manager.
     */
    public function __construct(
        Container $container,
        Config $config,
        AssetsManager $assets_manager
    ) {
        parent::__construct(
            $container,
            $config
        );

        $this->assets_manager = $assets_manager;
    }

    /**
     * Register the Theme Assets module.
     *
     * @return void
     */
    public function register(): void {

        $this->assets_manager->register();
    }
}