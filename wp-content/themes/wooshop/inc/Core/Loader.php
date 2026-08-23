<?php
/**
 * WooShop Loader.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Application loader.
 */
final class Loader
{

    /**
     * Module manager.
     *
     * @var ModuleManager
     */
    private ModuleManager $ModuleManager;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->ModuleManager = new ModuleManager();
    }

    /**
     * Load WooShop.
     *
     * @return void
     */
    public function boot(): void
    {

        $this->load_template_functions();

        $modules = require get_template_directory() . '/inc/Config/modules.php';

        if (!is_array($modules)) {
            return;
        }

        $this->ModuleManager->register($modules);
    }

    /**
     * Get module manager.
     *
     * @return ModuleManager
     */
    public function get_ModuleManager(): ModuleManager
    {
        return $this->ModuleManager;
    }

    /**
     * Load template compatibility functions.
     *
     * @return void
     */
    private function load_template_functions(): void
    {

        $file = get_template_directory() . '/inc/Modules/Theme/template-functions.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}