<?php
/**
 * WooCommerce Bootstrap Integration.
 *
 * Adds Bootstrap-compatible classes to WooCommerce fields and buttons.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Container;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Handles Bootstrap integration with WooCommerce.
 */
class Bootstrap extends Module
{

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct(Container $container)
    {

        parent::__construct($container);
    }

    /**
     * Register WooCommerce Bootstrap hooks.
     *
     * @return void
     */
    public function register(): void
    {

        add_filter(
            'woocommerce_form_field_args',
            [$this, 'filter_form_field'],
            10,
            1
        );

        add_filter(
            'woocommerce_loop_add_to_cart_link',
            [$this, 'filter_button_html'],
            10,
            1
        );
    }

    /**
     * Add Bootstrap classes to WooCommerce form fields.
     *
     * @param array $field Field arguments.
     * @return array
     */
    public function filter_form_field(array $field): array
    {

        if (isset($field['input_class'])) {
            $field['input_class'][] = 'form-control';
        } else {
            $field['input_class'] = ['form-control'];
        }

        return $field;
    }

    /**
     * Add Bootstrap classes to WooCommerce buttons.
     *
     * @param string $html Button HTML.
     * @return string
     */
    public function filter_button_html(string $html): string
    {

        return str_replace(
            'class="button',
            'class="button btn btn-primary',
            $html
        );
    }
}