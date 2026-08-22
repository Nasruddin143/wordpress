<?php
/**
 * Widgets Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme widgets module.
 */
final class Widgets extends Module {

    /**
     * Theme configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );

        $config = require get_template_directory()
            . '/inc/Config/theme.php';

        $this->config = is_array( $config ) ? $config : array();
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        add_action(
            'widgets_init',
            array( $this, 'register_sidebars' )
        );
    }

    /**
     * Register widget areas.
     *
     * @return void
     */
    public function register_sidebars(): void {

        $widget_areas = $this->config['widget_areas'] ?? array();

        foreach ( $widget_areas as $id => $area ) {

            if ( ! is_array( $area ) ) {
                continue;
            }

            register_sidebar(
                array(
                    'name'          => esc_html(
                        $area['name'] ?? ucfirst( (string) $id )
                    ),
                    'id'            => (string) $id,
                    'description'   => esc_html(
                        $area['description'] ?? ''
                    ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title h5 mb-3">',
                    'after_title'   => '</h2>',
                )
            );
        }
    }
}