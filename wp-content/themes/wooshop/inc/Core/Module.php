<?php
/**
 * Base Module
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

abstract class Module
{
    protected Container $container;

    final public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function register(): void;

    protected function service(string $id)
    {
        return $this->container->get($id);
    }

    protected function view(): View
    {
        return $this->service(
            View::class
        );
    }

    protected function config(): Config
    {
        return $this->service(
            Config::class
        );
    }

    protected function assets(): AssetManager
    {
        return $this->service(
            AssetManager::class
        );
    }
}