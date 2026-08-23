<?php
/**
 * Navigation Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use stdClass;
use WooShop\Core\Module;
use WooShop\Core\ModuleManager;
use WP_Post;

defined('ABSPATH') || exit;

/**
 * Navigation module.
 */
final class Navigation extends Module
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
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter(
            'nav_menu_link_attributes',
            array($this, 'link_attributes'),
            10,
            4
        );
    }

    /**
     * Modify navigation link attributes.
     *
     * @param array<string, string> $atts Attributes.
     * @param WP_Post $item Menu item.
     * @param stdClass $args Menu arguments.
     * @param int $depth Menu depth.
     *
     * @return array<string, string>
     */
    public function link_attributes(array $atts, WP_Post $item, stdClass $args, int $depth): array
    {

        if (isset($args->theme_location) && 'primary' === $args->theme_location) {
            $atts['class'] = isset($atts['class'])
                ? $atts['class'] . ' nav-link'
                : 'nav-link';
        }

        return $atts;
    }
}