<?php
/**
 * Converto_Module_Manager
 * Reads Converto_Config, instantiates enabled module classes,
 * and triggers their hook registration + asset enqueue logic.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Converto_Module_Manager {

    /** @var Converto_Module_Manager Singleton instance */
    private static $instance = null;

    /** @var array Holds instantiated module objects, keyed by slug */
    private $active_modules = array();

    /**
     * Returns singleton instance.
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Loads and boots all enabled modules.
     */
    public function load_modules() {
        foreach ( Converto_Config::get_modules() as $slug => $data ) {
            if ( empty( $data['enabled'] ) ) continue;

            $file = get_template_directory() . '/inc/modules/class-module-' . $slug . '.php';
            if ( ! file_exists( $file ) ) continue; // module not built yet

            require_once $file;

            if ( ! class_exists( $data['class'] ) ) continue;

            $module = new $data['class']();
            $module->register_hooks();

            $this->active_modules[ $slug ] = $module;
        }

        // Central hook so each module's enqueue_assets() runs on the front end.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_all_module_assets' ) );
    }

    /**
     * Loops all active modules and calls their enqueue_assets().
     */
    public function enqueue_all_module_assets() {
        foreach ( $this->active_modules as $module ) {
            $module->enqueue_assets();
        }
    }

    /**
     * Returns a single active module instance by slug (for template use).
     */
    public function get_module( $slug ) {
        return isset( $this->active_modules[ $slug ] ) ? $this->active_modules[ $slug ] : null;
    }
}