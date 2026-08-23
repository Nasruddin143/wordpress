<?php
/**
 * WooCommerce Setup Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * WooCommerce setup module.
 */
final class Setup extends Module
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

        if (!$this->is_available()) {
            return;
        }

        add_action('after_setup_theme', array($this, 'setup'), 20);
    }

    /**
     * Check WooCommerce availability.
     *
     * @return bool
     */
    private function is_available(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Register WooCommerce theme support.
     *
     * @return void
     */
    public function setup(): void
    {

        add_theme_support('woocommerce', array(
                'thumbnail_image_width' => 600,
                'single_image_width' => 900,
                'product_grid' => array(
                    'default_rows' => 3,
                    'min_rows' => 1,
                    'max_rows' => 6,
                    'default_columns' => 4,
                    'min_columns' => 1,
                    'max_columns' => 6,
                ),
            )
        );

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}