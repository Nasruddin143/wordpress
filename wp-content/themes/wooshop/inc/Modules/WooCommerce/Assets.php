<?php
/**
 * WooCommerce Assets Module
 *
 * Conditionally loads WooCommerce assets through
 * the centralized AssetsManager.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Class Assets
 *
 * Handles conditional WooCommerce asset loading.
 */
class Assets extends Module {

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
    public function __construct( Container $container ) {

        parent::__construct( $container );

        $this->assets = $container->get(
            AssetsManager::class
        );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue' ),
            20
        );
    }

    /**
     * Conditionally enqueue WooCommerce assets.
     *
     * @return void
     */
    public function enqueue(): void
    {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        if (
            is_shop() ||
            is_product_category() ||
            is_product_tag()
        ) {
            $this->assets->enqueue_woocommerce( 'base' );
            $this->assets->enqueue_woocommerce( 'shop' );

            return;
        }

        if ( is_product() ) {
            $this->assets->enqueue_woocommerce( 'base' );
            $this->assets->enqueue_woocommerce( 'product' );

            return;
        }

        if ( is_cart() ) {
            $this->assets->enqueue_woocommerce( 'base' );
            $this->assets->enqueue_woocommerce( 'cart' );

            return;
        }

        if ( is_checkout() ) {
            $this->assets->enqueue_woocommerce( 'base' );
            $this->assets->enqueue_woocommerce( 'checkout' );

            return;
        }

        if ( is_account_page() ) {
            $this->assets->enqueue_woocommerce( 'base' );
            $this->assets->enqueue_woocommerce( 'account' );
        }
    }
}