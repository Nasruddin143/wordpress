<?php
/**
 * Converto_Config
 * Central registry of all ecommerce modules and their enabled/disabled state.
 * Add a new module here first before creating its class.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Converto_Config {

    /**
     * Returns the master list of modules.
     * Key   = module slug (also used as component asset name in converto_enqueue_component)
     * Value = settings array
     */
    public static function get_modules() {
        return array(
            'product-filters'     => array( 'enabled' => true, 'class' => 'Converto_Module_Product_Filters' ),
            'variation-swatches'  => array( 'enabled' => true, 'class' => 'Converto_Module_Variation_Swatches' ),
            'wishlist'            => array( 'enabled' => true, 'class' => 'Converto_Module_Wishlist' ),
            'quick-view'          => array( 'enabled' => true, 'class' => 'Converto_Module_Quick_View' ),
            'advanced-reviews'    => array( 'enabled' => true, 'class' => 'Converto_Module_Advanced_Reviews' ),
            'size-guide'          => array( 'enabled' => true, 'class' => 'Converto_Module_Size_Guide' ),
            'compare-products'    => array( 'enabled' => true, 'class' => 'Converto_Module_Compare_Products' ),
            'product-brands'      => array( 'enabled' => true, 'class' => 'Converto_Module_Product_Brands' ),
            'stock-scarcity'      => array( 'enabled' => true, 'class' => 'Converto_Module_Stock_Scarcity' ),
            'free-shipping-bar'   => array( 'enabled' => true, 'class' => 'Converto_Module_Free_Shipping_Bar' ),
            'custom-tabs'         => array( 'enabled' => true, 'class' => 'Converto_Module_Custom_Tabs' ),
            'waitlist'            => array( 'enabled' => true, 'class' => 'Converto_Module_Waitlist' ),
            'product-videos'      => array( 'enabled' => true, 'class' => 'Converto_Module_Product_Videos' ),
            'sale-countdown'      => array( 'enabled' => true, 'class' => 'Converto_Module_Sale_Countdown' ),
            'social-sharing'      => array( 'enabled' => true, 'class' => 'Converto_Module_Social_Sharing' ),
            'payment-icons'       => array( 'enabled' => true, 'class' => 'Converto_Module_Payment_Icons' ),
            'mini-cart'           => array( 'enabled' => true, 'class' => 'Converto_Module_Mini_Cart' ),
        );
    }

    /**
     * Checks if a single module is enabled.
     */
    public static function is_enabled( $slug ) {
        $modules = self::get_modules();
        return isset( $modules[ $slug ] ) && $modules[ $slug ]['enabled'];
    }
}