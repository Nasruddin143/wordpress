<?php
/**
 * Slider Custom Post Type.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Slider post type module.
 */
final class Slider extends Module
{
    /**
     * Post type key.
     */
    private const string POST_TYPE = 'slider';

    /**
     * Constructor.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);
    }

    /**
     * Register module hooks.
     */
    public function register(): void
    {
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register Slider post type.
     */
    public function register_post_type(): void
    {
        $args = array(
            'label'         => esc_html__('Slider', 'wooshop'),
            'public'        => true,
            'show_in_rest'  => true, // Required for block theme / editor support
            'has_archive'   => true,
            'supports'      => array('title', 'editor', 'thumbnail'),
            'menu_icon'     => 'dashicons-store',
            'rewrite'       => array('slug' => 'sliders', 'with_front' => false),
        );

        register_post_type(self::POST_TYPE, $args);
    }
}
