<?php
/**
 * Module Manager.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Module manager.
 */
final class ModuleManager {

    /**
     * Registered modules.
     *
     * @var array<string, Module>
     */
    private array $modules = array();

    /**
     * Register modules from configuration.
     *
     * @param array<int, class-string<Module>> $module_classes Module classes.
     *
     * @return void
     */
    public function register( array $module_classes ): void {

        foreach ( $module_classes as $module_class ) {

            if ( ! class_exists( $module_class ) ) {
                continue;
            }

            $module = new $module_class( $this );

            if ( ! $module instanceof Module ) {
                continue;
            }

            $module->register();

            $this->modules[ $module_class ] = $module;
        }
    }

    /**
     * Get a registered module.
     *
     * @param class-string<Module> $module_class Module class.
     *
     * @return Module|null
     */
    public function get( string $module_class ): ?Module {
        return $this->modules[ $module_class ] ?? null;
    }

    /**
     * Check whether a module is registered.
     *
     * @param class-string<Module> $module_class Module class.
     *
     * @return bool
     */
    public function has( string $module_class ): bool {
        return isset( $this->modules[ $module_class ] );
    }
}