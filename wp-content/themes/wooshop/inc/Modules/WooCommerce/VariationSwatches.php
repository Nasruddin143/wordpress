<?php
/**
 * WooCommerce Variation Swatches Module
 *
 * Handles WooShop variation swatch mapping and data.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

class VariationSwatches extends Module
{

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if (!class_exists('WooCommerce')) {
            return;
        }

        add_filter(
            'wooshop_variation_swatch_type',
            [$this, 'get_swatch_type'],
            10,
            2
        );
    }


    /**
     * Determine variation swatch type.
     *
     * @param string $type      Swatch type.
     * @param string $attribute Attribute name.
     *
     * @return string
     */
    public function get_swatch_type(
        string $type,
        string $attribute
    ): string {

        if (!empty($type)) {
            return $type;
        }

        $attribute = sanitize_title(
            $attribute
        );

        $attribute = str_replace(
            'attribute_',
            '',
            $attribute
        );

        $attribute = str_replace(
            'pa_',
            '',
            $attribute
        );

        if (
            in_array(
                $attribute,
                [
                    'color',
                    'colour',
                ],
                true
            )
        ) {
            return 'color';
        }

        return 'label';
    }


    /**
     * Build variation swatch data.
     *
     * This method builds the base swatch data
     * and then exposes it through the
     * wooshop_variation_swatch_data filter.
     *
     * @param string $attribute Attribute name.
     * @param string $value     Attribute value.
     *
     * @return array
     */
    public function build_swatch_data(
        string $attribute,
        string $value
    ): array {

        $data = [
            'type' => '',
            'value' => $value,
            'color' => '',
            'image' => 0,
        ];

        $term_id = $this->get_attribute_term_id(
            $attribute,
            $value
        );

        /*
         * Custom product attribute.
         */
        if (!$term_id) {

            $data['type'] = apply_filters(
                'wooshop_variation_swatch_type',
                '',
                $attribute
            );

            return apply_filters(
                'wooshop_variation_swatch_data',
                $data,
                $attribute,
                $value
            );
        }

        /**
         * Get metadata service.
         *
         * @var VariationSwatchMeta $meta
         */
        $meta = $this->container->get(
            VariationSwatchMeta::class
        );

        $meta_data = $meta->get_data(
            $term_id
        );

        /*
         * Determine type.
         */
        $data['type'] = apply_filters(
            'wooshop_variation_swatch_type',
            $meta_data['type'],
            $attribute
        );

        /*
         * Color.
         */
        $data['color'] = $meta_data['color'];

        /*
         * Image attachment ID.
         */
        $data['image'] = $meta_data['image'];

        /*
         * Final swatch data filter.
         */
        return apply_filters(
            'wooshop_variation_swatch_data',
            $data,
            $attribute,
            $value
        );
    }


    /**
     * Get attribute term ID.
     *
     * @param string $attribute Attribute name.
     * @param string $value     Attribute value.
     *
     * @return int
     */
    private function get_attribute_term_id(
        string $attribute,
        string $value
    ): int {

        $taxonomy = sanitize_title(
            $attribute
        );

        /*
         * Convert:
         *
         * attribute_pa_color
         *
         * to:
         *
         * pa_color
         */
        $taxonomy = preg_replace(
            '/^attribute_/',
            '',
            $taxonomy
        );

        if (!$taxonomy) {
            return 0;
        }

        if (!taxonomy_exists($taxonomy)) {
            return 0;
        }

        $term = get_term_by(
            'slug',
            sanitize_title($value),
            $taxonomy
        );

        if (
            !$term ||
            is_wp_error($term)
        ) {
            return 0;
        }

        return (int) $term->term_id;
    }
}