<?php
/**
 * Base Module.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Abstract module class.
 */
abstract class Module
{

    /**
     * Module manager.
     *
     * @var ModuleManager
     */
    protected ModuleManager $manager;

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    abstract public function register(): void;
}