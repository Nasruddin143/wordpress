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
 * Widgets module.
 */
final class Widgets extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
    }

    /**
     * Register widget areas.
     *
     * @return void
     */
    public function register_sidebars(): void {

        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'wooshop' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__(
                    'Add widgets here.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 1', 'wooshop' ),
                'id'            => 'footer-1',
                'description'   => esc_html__(
                    'Add footer widgets here.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 2', 'wooshop' ),
                'id'            => 'footer-2',
                'description'   => esc_html__(
                    'Add footer widgets here.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }
}