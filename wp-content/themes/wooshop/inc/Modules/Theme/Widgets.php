<?php
/**
 * WooShop Widgets Module
 *
 * Registers all widget areas defined in theme.php config.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Widgets
 */
final class Widgets extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('widgets_init', [$this, 'register_sidebars']);
    }

    /**
     * Register all widget areas from theme.php config.
     *
     * Config shape (inc/Config/theme.php):
     *
     *   'widget_areas' => [
     *       'sidebar-1' => [
     *           'name'        => 'Sidebar',
     *           'description' => 'Add widgets here.',
     *           'before_widget' => '<div id="%1$s" class="widget %2$s">',
     *           'after_widget'  => '</div>',
     *           'before_title'  => '<h2 class="widget-title">',
     *           'after_title'   => '</h2>',
     *       ],
     *   ],
     *
     * @return void
     */
    public function register_sidebars(): void
    {
        $areas = $this->container->get('config')->get('theme')['widget_areas'] ?? [];

        if (empty($areas)) {
            return;
        }

        foreach ($areas as $id => $area) {
            register_sidebar([
                'id'            => sanitize_key($id),
                'name'          => $area['name']          ?? ucfirst($id),
                'description'   => $area['description']   ?? '',
                'before_widget' => $area['before_widget']
                    ?? '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => $area['after_widget']  ?? '</div>',
                'before_title'  => $area['before_title']  ?? '<h2 class="widget-title">',
                'after_title'   => $area['after_title']   ?? '</h2>',
            ]);
        }
    }
}