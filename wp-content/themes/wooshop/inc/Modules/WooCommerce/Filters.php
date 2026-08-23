<?php
/**
 * WooCommerce Filters Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * WooCommerce filters module.
 */
final class Filters extends Module
{

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if (!class_exists('WooCommerce')) {
            return;
        }

        add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start'));

        add_filter('woocommerce_product_loop_end', array($this, 'product_loop_end'));
    }

    /**
     * Add Bootstrap product grid.
     *
     * @param string $html Original loop markup.
     *
     * @return string
     */
    public function product_loop_start(string $html): string
    {

        unset($html);

        return '<ul class="products row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4 list-unstyled">';
    }

    /**
     * Close Bootstrap product grid.
     *
     * @param string $html Original loop markup.
     *
     * @return string
     */
    public function product_loop_end(string $html): string
    {

        unset($html);

        return '</ul>';
    }
}