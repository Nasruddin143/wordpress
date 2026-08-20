<?php
/**
 * WooCommerce Shop Module
 *
 * Handles WooCommerce shop/archive presentation.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce\Shop;

use WooShop\Core\Module;
use WooShop\Core\View;

defined( 'ABSPATH' ) || exit;

class Shop extends Module
{
    /**
     * Register module.
     */
    public function register(): void
    {
        add_action(
            'woocommerce_no_products_found',
            [ $this, 'render_empty_state' ]
        );
    }

    /**
     * Render empty shop state.
     */
    public function render_empty_state(): void
    {
        $view = $this->container->get( View::class );

        $view->render(
            'woocommerce/shop/no-products'
        );
    }
}