<?php

namespace KT\Integrations;

defined('ABSPATH') || exit;

class WCommon
{
    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */
        add_action('after_setup_theme',            [$this, 'setup']);
        add_action('after_setup_theme',            [$this, 'remove_defaults']);
        add_action('wp_enqueue_scripts',            [$this, 'enqueue_scripts']);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */
        add_filter('woocommerce_enqueue_styles',            '__return_empty_array');
        add_filter('body_class',            [$this, 'body_class']);
        add_filter('woocommerce_output_related_products_args',            [$this, 'related_products']);
        add_filter('woocommerce_add_to_cart_fragments',            [$this, 'cart_fragment']);
        add_filter('get_avatar',            [$this, 'custom_review_avatar']);
        add_filter('woocommerce_loop_add_to_cart_args',            [$this, 'loop_add_to_cart_classes'],            10,            2);
        add_filter('woocommerce_product_single_add_to_cart_text',            [$this, 'single_add_to_cart_text']);
        add_filter('woocommerce_catalog_orderby',            [$this, 'catalog_orderby']);

        /*
        |--------------------------------------------------------------------------
        | Custom Wrappers
        |--------------------------------------------------------------------------
        */

        add_action('woocommerce_before_main_content',            [$this, 'wrapper_start'],            10);

        add_action('woocommerce_after_main_content',            [$this, 'wrapper_end'],            10);
    }

    /**
     * Theme setup
     */
    public function setup()
    {
        //add_theme_support('woocommerce');
    }

    /**
     * Remove WooCommerce defaults
     */
    public function remove_defaults()
    {
        /*
        |--------------------------------------------------------------------------
        | Default Wrappers
        |--------------------------------------------------------------------------
        */
        remove_action('woocommerce_before_main_content',            'woocommerce_output_content_wrapper',            10);
        remove_action('woocommerce_after_main_content',            'woocommerce_output_content_wrapper_end',            10);

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */
        remove_action('woocommerce_sidebar',            'woocommerce_get_sidebar',            10);

        /*
        |--------------------------------------------------------------------------
        | Product UL/LI Wrappers
        |--------------------------------------------------------------------------
        */
        add_filter('woocommerce_product_loop_start',            '__return_empty_string');
        add_filter('woocommerce_product_loop_end',            '__return_empty_string');
    }

    /**
     * Scripts & styles
     */
    public function enqueue_scripts()
    {
        if (is_shop() || is_product_taxonomy()) {
        }
    }

    /**
     * Body classes
     */
    public function body_class($classes)
    {
        $classes[] = 'woocommerce-active';

        return $classes;
    }

    /**
     * Related products
     */
    public function related_products($args)
    {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;

        return $args;
    }

    /**
     * Wrapper start
     */
    public function wrapper_start() {}

    /**
     * Wrapper end
     */
    public function wrapper_end() {}

    /**
     * Cart fragments
     */
    public function cart_fragment($fragments)
    {
        ob_start();

        $this->cart_link();

        $fragments['a.cart-contents']
            = ob_get_clean();

        return $fragments;
    }

    /**
     * Cart link
     */
    public function cart_link()
    {
        if (!function_exists('WC') || !WC()->cart) {
            return;
        }

        $count = WC()->cart->get_cart_contents_count();

?>

        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>">

            <span class="amount">

                <?php echo wp_kses_data(
                    WC()->cart->get_cart_subtotal()
                ); ?>

            </span>

            <span class="count">

                <?php
                printf(
                    esc_html(
                        _n(
                            '%d item',
                            '%d items',
                            $count,
                            'kt'
                        )
                    ),
                    $count
                );
                ?>

            </span>

        </a>

<?php
    }

    /**
     * Review avatar
     */
    public function custom_review_avatar($avatar)
    {
        if (!is_product()) {
            return $avatar;
        }

        return preg_replace(
            '/class=[\'"](.*?)[\'"]/',
            'class="img-fluid rounded-circle"',
            $avatar
        );
    }

    /**
     * Loop add to cart classes
     */
    public function loop_add_to_cart_classes($args, $product)
    {
        $args['class'] .=
            ' btn btn-primary w-100';

        return $args;
    }

    /**
     * Single add to cart text
     */
    public function single_add_to_cart_text($text)
    {
        return __('Add To Cart', 'kt');
    }


    /**
     * Custom Orderby
     */
    public function catalog_orderby($options)
    {
        return [
            'menu_order' => __('Default Sorting', 'kt'),
            'popularity' => __('Popularity', 'kt'),
            'rating' => __('Top Rated', 'kt'),
            'date' => __('Latest Arrivals', 'kt'),
            'price' => __('Price: Low to High', 'kt'),
            'price-desc' => __('Price: High to Low', 'kt'),
        ];
    }
}
