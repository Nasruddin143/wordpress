<?php
namespace KT\Integrations;

defined('ABSPATH') || exit;

class WooCommerce {

    public function __construct() {

        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);

        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_filter('body_class', [$this, 'body_class']);
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products']);

        add_action('init', [$this, 'override_wrappers']);

        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragment']);
    }

    /**
     * ----------------------------------------
     * Setup WooCommerce Support
     * ----------------------------------------
     */
    public function setup() {

        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 150,
            'single_image_width'    => 300,
            'product_grid' => [
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ],
        ]);

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * ----------------------------------------
     * Enqueue Styles
     * ----------------------------------------
     */
    public function enqueue() {

        wp_enqueue_style(
            'kt-woocommerce',
            get_template_directory_uri() . '/woocommerce.css',
            [],
            $this->version('/woocommerce.css')
        );

        // Star rating font (optional optimization)
        if (function_exists('WC')) {
            $font_path = WC()->plugin_url() . '/assets/fonts/';

            $inline_font = "@font-face {
                font-family: 'star';
                src: url('{$font_path}star.woff') format('woff');
                font-weight: normal;
                font-style: normal;
            }";

            wp_add_inline_style('kt-woocommerce', $inline_font);
        }
    }

    /**
     * ----------------------------------------
     * Body Class
     * ----------------------------------------
     */
    public function body_class($classes) {
        $classes[] = 'woocommerce-active';
        return $classes;
    }

    /**
     * ----------------------------------------
     * Related Products
     * ----------------------------------------
     */
    public function related_products($args) {

        return wp_parse_args([
            'posts_per_page' => 3,
            'columns'        => 3,
        ], $args);
    }

    /**
     * ----------------------------------------
     * Wrapper Override
     * ----------------------------------------
     */
    public function override_wrappers() {

        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

        add_action('woocommerce_before_main_content', [$this, 'wrapper_before']);
        add_action('woocommerce_after_main_content', [$this, 'wrapper_after']);
    }

    public function wrapper_before() {
        echo '<main id="primary" class="site-main">';
    }

    public function wrapper_after() {
        echo '</main>';
    }

    /**
     * ----------------------------------------
     * Cart Fragment (AJAX)
     * ----------------------------------------
     */
    public function cart_fragment($fragments) {

        ob_start();
        echo $this->cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }

    /**
     * ----------------------------------------
     * Cart Link HTML
     * ----------------------------------------
     */
    public function cart_link() {

        if (!function_exists('WC') || !WC()->cart) {
            return '';
        }

        $count = WC()->cart->get_cart_contents_count();

        $text = sprintf(
            _n('%d item', '%d items', $count, 'kt'),
            $count
        );

        ob_start();
        ?>

        <a class="cart-contents"
           href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'kt'); ?>">

            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
            <span class="count"><?php echo esc_html($text); ?></span>

        </a>

        <?php
        return ob_get_clean();
    }

    /**
     * ----------------------------------------
     * Header Cart
     * ----------------------------------------
     */
    public function header_cart() {

        $class = is_cart() ? 'current-menu-item' : '';

        ?>
        <ul id="site-header-cart" class="site-header-cart">

            <li class="<?php echo esc_attr($class); ?>">
                <?php echo $this->cart_link(); ?>
            </li>

            <li>
                <?php the_widget('WC_Widget_Cart', ['title' => '']); ?>
            </li>

        </ul>
        <?php
    }

    /**
     * ----------------------------------------
     * Asset Version Helper
     * ----------------------------------------
     */
    private function version($file) {

        $path = get_template_directory() . $file;

        return file_exists($path) ? filemtime($path) : '1.0';
    }
}