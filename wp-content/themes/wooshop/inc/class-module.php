<?php
/**
 * Converto_Module (abstract)
 * Base class every feature module must extend.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Converto_Module {

    /** @var string Unique module slug, matches Config key and asset component name */
    protected $slug = '';

    /**
     * Called by the Module Manager once the module is instantiated.
     * Register all hooks (actions/filters) here.
     */
    abstract public function register_hooks();

    /**
     * Called on the front end to conditionally enqueue this module's CSS/JS
     * via converto_enqueue_component(). Must decide internally whether the
     * current page needs the assets (e.g. is_product(), is_shop(), etc.).
     */
    abstract public function enqueue_assets();

    /**
     * Returns the module slug.
     */
    public function get_slug() {
        return $this->slug;
    }
}