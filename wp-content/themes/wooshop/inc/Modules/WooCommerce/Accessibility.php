<?php
/**
 * WooShop WooCommerce Accessibility Module
 *
 * Adds ARIA labels, improves keyboard navigation, and
 * fixes known WooCommerce accessibility gaps.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Accessibility
 */
final class Accessibility extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        // Add screen-reader labels to add-to-cart buttons on archive pages.
        add_filter('woocommerce_loop_add_to_cart_args',  [$this, 'add_to_cart_aria_label'], 10, 2);

        // Add aria-label to the quantity input.
        add_filter('woocommerce_quantity_input_args',    [$this, 'quantity_input_aria']);

        // Add role="status" to WooCommerce notices so screen readers announce them.
        add_filter('woocommerce_add_message',            [$this, 'wrap_notice_message']);
        add_filter('woocommerce_add_error',              [$this, 'wrap_notice_error']);

        // Add loading="lazy" to WooCommerce product images.
        add_filter('woocommerce_product_get_image',      [$this, 'lazy_load_image']);
    }

    /**
     * Add a descriptive aria-label to loop add-to-cart buttons.
     *
     * Without this, every button on a shop archive reads as "Add to cart"
     * with no product context for screen readers.
     *
     * @param array<string, mixed> $args    Button arguments.
     * @param \WC_Product          $product Product object.
     *
     * @return array<string, mixed>
     */
    public function add_to_cart_aria_label(array $args, \WC_Product $product): array
    {
        $args['aria-label'] = sprintf(
        /* translators: %s: product name */
            esc_attr__('Add "%s" to your cart', 'wooshop'),
            $product->get_name()
        );

        return $args;
    }

    /**
     * Add an aria-label to the quantity input field.
     *
     * @param array<string, mixed> $args Quantity input arguments.
     *
     * @return array<string, mixed>
     */
    public function quantity_input_aria(array $args): array
    {
        if (empty($args['input_id'])) {
            return $args;
        }

        $args['aria_label'] = esc_attr__('Product quantity', 'wooshop');

        return $args;
    }

    /**
     * Wrap a success notice message in a role="status" container.
     *
     * @param string $message Notice HTML.
     *
     * @return string
     */
    public function wrap_notice_message(string $message): string
    {
        return '<span role="status">' . $message . '</span>';
    }

    /**
     * Wrap an error notice in a role="alert" container so it is
     * announced immediately by screen readers.
     *
     * @param string $error Error HTML.
     *
     * @return string
     */
    public function wrap_notice_error(string $error): string
    {
        return '<span role="alert">' . $error . '</span>';
    }

    /**
     * Add loading="lazy" to WooCommerce product images that
     * do not already carry an explicit loading attribute.
     *
     * @param string $image Product image HTML.
     *
     * @return string
     */
    public function lazy_load_image(string $image): string
    {
        if (str_contains($image, 'loading=')) {
            return $image;
        }

        return str_replace('<img ', '<img loading="lazy" ', $image);
    }
}