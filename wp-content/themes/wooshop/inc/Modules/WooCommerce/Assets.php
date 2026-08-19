<?php
/**
 * WooShop WooCommerce Assets Module
 *
 * Boots the centralized asset manager for WooCommerce.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Class Assets
 *
 * Provides the WooCommerce asset integration point.
 */
class Assets extends Module {

    /**
     * Assets manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets_manager;

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        $this->assets_manager = $this->container->get(
            AssetsManager::class
        );

    }

}