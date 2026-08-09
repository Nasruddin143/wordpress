<?php
/**
 * WooCommerce Product Card Module
 *
 * Handles WooCommerce product card rendering.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\View;

defined( 'ABSPATH' ) || exit;

class ProductCard extends Module
{

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        add_action(
            'wooshop_product_card',
            [ $this, 'render' ]
        );

        add_action(
            'wooshop_product_card_media',
            [ $this, 'media' ]
        );

        add_action(
            'wooshop_product_card_category',
            [ $this, 'category' ]
        );

        add_action(
            'wooshop_product_card_info',
            [ $this, 'info' ]
        );

        add_action(
            'wooshop_product_card_actions',
            [ $this, 'actions' ]
        );
    }

    /**
     * Render product card.
     *
     * @return void
     */
    public function render(): void
    {

        /** @var View $view */
        $view = $this->container->get( View::class );

        $view->render( 'woocommerce/product-card/product-card' );
    }

    /**
     * Render product media.
     *
     * @return void
     */
    public function media(): void
    {

        /** @var View $view */
        $view = $this->container->get( View::class );

        $view->render( 'woocommerce/product-card/media' );
    }

    /**
     * Render product category.
     *
     * @return void
     */
    public function category(): void
    {

        /** @var View $view */
        $view = $this->container->get( View::class );

        $view->render( 'woocommerce/product-card/category' );
    }

    /**
     * Render product information.
     *
     * @return void
     */
    public function info(): void
    {

        /** @var View $view */
        $view = $this->container->get( View::class );

        $view->render( 'woocommerce/product-card/info' );
    }

    /**
     * Render product actions.
     *
     * @return void
     */
    public function actions(): void
    {

        /** @var View $view */
        $view = $this->container->get( View::class );

        $view->render( 'woocommerce/product-card/actions' );
    }

}