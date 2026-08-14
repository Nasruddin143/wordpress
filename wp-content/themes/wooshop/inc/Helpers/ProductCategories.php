<?php
/**
 * Product Categories Helper
 *
 * @package WooShop
 */

namespace WooShop\Helpers;

defined('ABSPATH') || exit;

class ProductCategories
{

    /**
     * Return product categories.
     *
     * @param array $args Query arguments.
     *
     * @return array
     */
    public static function get(array $args = []): array
    {

        if (!taxonomy_exists('product_cat')) {
            return [];
        }

        $defaults = [

                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 0,
                'orderby' => 'menu_order',
                'order' => 'ASC',

        ];

        $terms = get_terms(
                wp_parse_args($args, $defaults)
        );

        if (is_wp_error($terms)) {
            return [];
        }

        return $terms;
    }

    /**
     * Render category dropdown.
     *
     * @param string $name Selected field name.
     * @param string $selected Selected slug.
     *
     * @return void
     */
    public static function dropdown(
            string $name = 'product_cat',
            string $selected = ''
    ): void
    {

        $terms = self::get();
        ?>

        <select
                class="form-select ws-search__category"
                name="<?php echo esc_attr($name); ?>"
        >

            <option value="">
                <?php esc_html_e('All Categories', 'wooshop'); ?>
            </option>

            <?php foreach ($terms as $term) : ?>

                <option
                        value="<?php echo esc_attr($term->slug); ?>"
                        <?php selected($selected, $term->slug); ?>
                >

                    <?php echo esc_html($term->name); ?>

                </option>

            <?php endforeach; ?>

        </select>

        <?php
    }
}