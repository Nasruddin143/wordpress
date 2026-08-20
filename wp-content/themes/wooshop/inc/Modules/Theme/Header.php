<?php
/**
 * Custom Header Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Custom header module.
 */
final class Header extends Module {

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
        add_action(
            'after_setup_theme',
            array( $this, 'register_header' )
        );
    }

    /**
     * Register custom header.
     *
     * @return void
     */
    public function register_header(): void {

        add_theme_support(
            'custom-header',
            array(
                'default-image'      => '',
                'default-text-color' => '000000',
                'width'              => 1920,
                'height'             => 400,
                'flex-height'        => true,
                'flex-width'         => true,
                'wp-head-callback'   => array( $this, 'header_style' ),
            )
        );
    }

    /**
     * Output custom header CSS.
     *
     * @return void
     */
    public function header_style(): void {

        $header_text_color = get_header_textcolor();

        if ( ! display_header_text() ) {
            return;
        }

        ?>
        <style type="text/css">
            .site-title,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }
        </style>
        <?php
    }
}