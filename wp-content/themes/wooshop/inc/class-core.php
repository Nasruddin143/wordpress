<?php
/**
 * Converto_Core
 * Theme bootstrap. Loads config/module system and initializes it on 'after_setup_theme'.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Converto_Core {

    /** @var Converto_Core Singleton instance */
    private static $instance = null;

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
     * Constructor: requires core files and hooks module boot.
     */
    private function __construct() {
        $this->includes();
        add_action( 'init', array( $this, 'boot_modules' ) );
    }

    /**
     * Requires the core architecture files.
     */
    private function includes() {
        require_once get_template_directory() . '/inc/class-config.php';
        require_once get_template_directory() . '/inc/class-module.php';
        require_once get_template_directory() . '/inc/class-module-manager.php';
    }

    /**
     * Boots the Module Manager, which loads all enabled feature modules.
     */
    public function boot_modules() {
        Converto_Module_Manager::instance()->load_modules();
    }
}