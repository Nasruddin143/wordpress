<?php
/**
 * WooShop WooCommerce Filters Module
 *
 * Customizes WooCommerce output via filters:
 * sale badge, product tabs, loop markup, and more.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WC_Product;
use WooShop\Core\Module;
use WP_Post;

defined('ABSPATH') || exit;

/**
 * Class Filters
 */
final class Filters extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        // Sale badge text.
        add_filter('woocommerce_sale_flash',              [$this, 'sale_badge'], 10, 3);

        // Product tabs on single product pages.
        add_filter('woocommerce_product_tabs',            [$this, 'reorder_product_tabs'], 98);

        // Related products count and columns.
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products_args']);

        // Breadcrumb defaults.
        add_filter('woocommerce_breadcrumb_defaults',     [$this, 'breadcrumb_defaults']);

        // Add-to-cart button classes.
        add_filter('woocommerce_loop_add_to_cart_args',   [$this, 'add_to_cart_classes'], 10, 2);

        // Remove cross-sells on cart page.
        add_action('init',                                [$this, 'remove_cart_cross_sells']);

        // Placeholder image.
        add_filter('woocommerce_placeholder_img_src',     [$this, 'placeholder_image']);
    }

    /**
     * Replace the plain "Sale!" badge with a percentage discount.
     *
     * Shows "−30%" instead of "Sale!" when the regular price is known.
     *
     * @param string      $html    Default badge HTML.
     * @param WP_Post    $post    Post object.
     * @param WC_Product $product Product object.
     *
     * @return string
     */
    public function sale_badge(string $html, WP_Post $post, WC_Product $product): string
    {
        if ($product->is_type('variable')) {
            $percentages = [];

            foreach ($product->get_children() as $child_id) {
                $variation = wc_get_product($child_id);

                if (
                    $variation &&
                    $variation->is_on_sale() &&
                    $variation->get_regular_price()
                ) {
                    $percentages[] = round(
                        (($variation->get_regular_price() - $variation->get_sale_price())
                            / $variation->get_regular_price()) * 100
                    );
                }
            }

            if (empty($percentages)) {
                return $html;
            }

            $pct = max($percentages);
        } else {
            $regular = $product->get_regular_price();
            $sale    = $product->get_sale_price();

            if (!$regular || !$sale) {
                return $html;
            }

            $pct = round((($regular - $sale) / $regular) * 100);
        }

        return sprintf(
            '<span class="onsale">-%d%%</span>',
            $pct
        );
    }

    /**
     * Reorder product tabs: Description → Reviews → Attributes.
     *
     * @param array<string, array<string, mixed>> $tabs Registered product tabs.
     *
     * @return array<string, array<string, mixed>>
     */
    public function reorder_product_tabs(array $tabs): array
    {
        if (isset($tabs['description'])) {
            $tabs['description']['priority'] = 10;
        }

        if (isset($tabs['reviews'])) {
            $tabs['reviews']['priority'] = 20;
        }

        if (isset($tabs['additional_information'])) {
            $tabs['additional_information']['priority'] = 30;
        }

        return $tabs;
    }

    /**
     * Limit related products to 3, displayed in a single row.
     *
     * @param array<string, int> $args Related products query args.
     *
     * @return array<string, int>
     */
    public function related_products_args(array $args): array
    {
        $count = $this->container->get('config')->get('theme')['woo_related_products'] ?? 3;

        $args['posts_per_page'] = (int) $count;
        $args['columns']        = (int) $count;

        return $args;
    }

    /**
     * Customize breadcrumb separator and home label.
     *
     * @param array<string, mixed> $defaults WooCommerce breadcrumb defaults.
     *
     * @return array<string, mixed>
     */
    public function breadcrumb_defaults(array $defaults): array
    {
        $defaults['delimiter']   = ' <span aria-hidden="true">/</span> ';
        $defaults['home']        = _x('Home', 'breadcrumb home label', 'wooshop');
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="'
            . esc_attr__('Breadcrumb', 'wooshop') . '">';
        $defaults['wrap_after']  = '</nav>';

        return $defaults;
    }

    /**
     * Add BEM class to loop add-to-cart buttons.
     *
     * @param array<string, mixed> $args    Button arguments.
     * @param WC_Product          $product Product object.
     *
     * @return array<string, mixed>
     */
    public function add_to_cart_classes(array $args, WC_Product $product): array
    {
        $existing = $args['class'] ?? '';
        $args['class'] = trim($existing . ' button button--add-to-cart');

        return $args;
    }

    /**
     * Remove cross-sell products from the cart page.
     *
     * Cross-sells add visual noise on the cart; upsells on the
     * product page are kept.
     *
     * @return void
     */
    public function remove_cart_cross_sells(): void
    {
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    }

    /**
     * Point the missing-image placeholder to a theme asset.
     *
     * The file must exist at assets/images/placeholder.png inside the theme.
     *
     * @param string $src Default WooCommerce placeholder URL.
     *
     * @return string
     */
    public function placeholder_image(string $src): string
    {
        $theme_dir  = get_template_directory();
        $local_path = '/assets/images/placeholder.png';

        if (file_exists($theme_dir . $local_path)) {
            return get_template_directory_uri() . $local_path;
        }

        return $src;
    }
}