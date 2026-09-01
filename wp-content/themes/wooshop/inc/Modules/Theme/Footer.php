<?php
/**
 * WooShop Footer Module
 *
 * Handles footer-related hooks and widget areas.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Footer
 */
final class Footer extends Module
{
    /**
     * Cached theme configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        /*
         * Pull theme configuration once via the container.
         */
        $this->config = $this->container->get('config')->get('theme');

        /*
         * Register footer widget areas.
         */
        add_action('widgets_init', [$this, 'register_widget_areas']);

        /*
         * Render footer markup.
         *
         * Called from footer.php via:
         *
         * do_action('wooshop_footer');
         */
        add_action('wooshop_footer', [$this, 'render_footer']);
    }

    /**
     * Register footer widget areas.
     *
     * @return void
     */
    public function register_widget_areas(): void
    {
        $widget_areas = $this->config['widget_areas'] ?? [];

        if (!is_array($widget_areas)) {
            return;
        }

        foreach ($widget_areas as $id => $widget_area) {
            if (!is_string($id) || !str_starts_with($id, 'footer-') || !is_array($widget_area)) {
                continue;
            }

            $name = $widget_area['name'] ?? '';
            $description = $widget_area['description'] ?? '';

            if (!is_string($name) || $name === '') {
                continue;
            }

            register_sidebar(
                [
                    'name' => $name,
                    'id' => $id,
                    'description' => is_string($description) ? $description : '',
                    'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                    'after_widget' => '</section>',
                    'before_title' => '<h2 class="widget-title h5 mb-3">',
                    'after_title' => '</h2>',
                ]
            );
        }
    }

    /**
     * Render the site footer.
     *
     * @return void
     */
    public function render_footer(): void
    {
        get_template_part('template-parts/footer/footer', null, ['config' => $this->config,]);
    }
}
