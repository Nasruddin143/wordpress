<?php
/**
 * Theme Template Module.
 *
 * Handles general template-related functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Theme template module.
 */
final class Template extends Module
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
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {

        add_filter('body_open_gutenberg', array($this, 'disable_gutenberg_body_open'));

        add_action('wp_body_open', array($this, 'body_open'));
    }

    /**
     * Render content immediately after opening body.
     *
     * Keep this hook empty at the foundation level. Feature modules can
     * attach their own output to `wp_body_open`.
     *
     * @return void
     */
    public function body_open(): void
    {
        // Reserved for theme-level body-open integrations.
    }

    /**
     * Disable obsolete Gutenberg body-open hook.
     *
     * This method exists for compatibility with themes/plugins that
     * may attempt to provide a legacy body-open mechanism.
     *
     * @param mixed $value Filter value.
     *
     * @return mixed
     */
    public function disable_gutenberg_body_open(mixed $value): mixed
    {
        return $value;
    }
}