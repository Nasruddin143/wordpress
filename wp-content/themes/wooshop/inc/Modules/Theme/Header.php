<?php
/**
 * Custom Header Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Custom header module.
 */
final class Header extends Module
{

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
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);

        $config = require get_template_directory() . '/inc/Config/theme.php';

        $this->config = is_array($config) ? $config : array();
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', array($this, 'register_custom_header'));

        add_action('wp_head', array($this, 'header_style'));
    }

    /**
     * Register custom header support.
     *
     * @return void
     */
    public function register_custom_header(): void
    {

        $args = $this->config['custom_header'] ?? array();

        if (!empty($args)) {
            add_theme_support(
                    'custom-header',
                    $args
            );
        }
    }

    /**
     * Output custom header text color styles.
     *
     * @return void
     */
    public function header_style(): void
    {

        if (!display_header_text()) {
            return;
        }

        $color = get_header_textcolor();

        if (!$color) {
            return;
        }

        ?>
        <style>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr( $color ); ?>;
            }
        </style>
        <?php
    }
}