<?php
/**
 * WooShop Product Filters
 *
 * Provides AJAX-powered product filtering for WooCommerce archives.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;
use WP_Query;

defined("ABSPATH") || exit();

/**
 * ProductFilters class.
 */
class ProductFilters extends Module
{
    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     * @param AssetsManager             $assets    Asset manager.
     */
    public function __construct(Container $container, AssetsManager $assets)
    {
        parent::__construct($container);

        $this->assets = $assets;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action("wp_enqueue_scripts", [$this, "enqueue_assets"], 30);

        add_action("wp_ajax_wooshop_filter_products", [
                $this,
                "filter_products",
        ]);

        add_action("wp_ajax_nopriv_wooshop_filter_products", [
                $this,
                "filter_products",
        ]);

        add_action(
                "woocommerce_before_shop_loop",
                [$this, "render_filters"],
                15
        );
    }

    /**
     * Enqueue product filter assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        if (!$this->is_product_archive()) {
            return;
        }

        $this->assets->enqueue_component("product-filters");

        wp_localize_script("wooshop-product-filters", "WooShopProductFilters", [
                "ajaxUrl" => admin_url("admin-ajax.php"),
                "nonce" => wp_create_nonce("wooshop_product_filters"),
                "action" => "wooshop_filter_products",
        ]);
    }

    /**
     * Determine whether the current page is a product archive.
     *
     * @return bool
     */
    protected function is_product_archive(): bool
    {
        if (!class_exists("WooCommerce")) {
            return false;
        }

        return is_shop() ||
                is_product_category() ||
                is_product_tag() ||
                is_product_taxonomy();
    }

    /**
     * Render product filters.
     *
     * @return void
     */
    public function render_filters(): void
    {
        if (!$this->is_product_archive()) {
            return;
        }

        $attributes = $this->get_filter_attributes();
        $categories = $this->get_product_categories();
        $min_price = $this->get_min_price();
        $max_price = $this->get_max_price();
        ?>
        <div
                class="ws-product-filters"
                data-ws-product-filters
        >

            <button
                    type="button"
                    class="btn btn-outline-secondary ws-product-filters__toggle d-lg-none"
                    data-ws-filter-toggle
                    aria-expanded="false"
                    aria-controls="ws-product-filters-panel"
            >
                <?php esc_html_e("Filters", "wooshop"); ?>
            </button>

            <div
                    id="ws-product-filters-panel"
                    class="ws-product-filters__panel"
                    data-ws-filter-panel
            >

                <div class="ws-product-filters__header">

                    <h2 class="h5 mb-0">
                        <?php esc_html_e("Filter products", "wooshop"); ?>
                    </h2>

                    <button
                            type="button"
                            class="btn-close d-lg-none"
                            data-ws-filter-close
                            aria-label="<?php esc_attr_e(
                                    "Close filters",
                                    "wooshop"
                            ); ?>"
                    ></button>

                </div>

                <div class="ws-product-filters__body">

                    <?php
                    $this->render_category_filter($categories);

                    $this->render_price_filter($min_price, $max_price);

                    foreach ($attributes as $attribute) {
                        $this->render_attribute_filter($attribute);
                    }
                    ?>

                </div>

                <div class="ws-product-filters__footer">

                    <button
                            type="button"
                            class="btn btn-primary w-100"
                            data-ws-apply-filters
                    >
                        <?php esc_html_e("Apply filters", "wooshop"); ?>
                    </button>

                    <button
                            type="button"
                            class="btn btn-link w-100"
                            data-ws-clear-filters
                    >
                        <?php esc_html_e("Clear all", "wooshop"); ?>
                    </button>

                </div>

            </div>
        </div>
        <?php
    }

    /**
     * Get product categories.
     *
     * @return array
     */
    protected function get_product_categories(): array
    {
        $terms = get_terms([
                "taxonomy" => "product_cat",
                "hide_empty" => true,
                "parent" => 0,
        ]);

        return is_wp_error($terms) ? [] : $terms;
    }

    /**
     * Get filterable product attributes.
     *
     * @return array
     */
    protected function get_filter_attributes(): array
    {
        $attributes = wc_get_attribute_taxonomies();

        if (empty($attributes)) {
            return [];
        }

        $result = [];

        foreach ($attributes as $attribute) {
            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);

            if (!taxonomy_exists($taxonomy)) {
                continue;
            }

            $terms = get_terms([
                    "taxonomy" => $taxonomy,
                    "hide_empty" => true,
            ]);

            if (is_wp_error($terms) || empty($terms)) {
                continue;
            }

            $result[] = [
                    "name" => $attribute->attribute_name,
                    "label" => $attribute->attribute_label,
                    "taxonomy" => $taxonomy,
                    "terms" => $terms,
            ];
        }

        return $result;
    }

    /**
     * Render category filter.
     *
     * @param array $categories Product categories.
     * @return void
     */
    protected function render_category_filter(array $categories): void
    {
        if (empty($categories)) {
            return;
        } ?>
        <fieldset class="ws-product-filter">

            <legend class="ws-product-filter__title">
                <?php esc_html_e("Categories", "wooshop"); ?>
            </legend>

            <?php foreach ($categories as $category): ?>

                <label class="form-check">

                    <input
                            type="checkbox"
                            class="form-check-input"
                            name="product_cat[]"
                            value="<?php echo esc_attr($category->slug); ?>"
                            data-ws-filter
                    >

                    <span class="form-check-label">
						<?php echo esc_html($category->name); ?>
					</span>

                </label>

            <?php endforeach; ?>

        </fieldset>
        <?php
    }

    /**
     * Render price filter.
     *
     * @param float $min_price Minimum price.
     * @param float $max_price Maximum price.
     * @return void
     */
    protected function render_price_filter(
            float $min_price,
            float $max_price
    ): void {
        ?>
        <fieldset class="ws-product-filter">

            <legend class="ws-product-filter__title">
                <?php esc_html_e("Price", "wooshop"); ?>
            </legend>

            <div class="row g-2">

                <div class="col-6">

                    <label
                            class="form-label"
                            for="ws-filter-min-price"
                    >
                        <?php esc_html_e("Min", "wooshop"); ?>
                    </label>

                    <input
                            id="ws-filter-min-price"
                            type="number"
                            class="form-control"
                            name="min_price"
                            min="0"
                            value="<?php echo esc_attr($min_price); ?>"
                            data-ws-filter
                    >

                </div>

                <div class="col-6">

                    <label
                            class="form-label"
                            for="ws-filter-max-price"
                    >
                        <?php esc_html_e("Max", "wooshop"); ?>
                    </label>

                    <input
                            id="ws-filter-max-price"
                            type="number"
                            class="form-control"
                            name="max_price"
                            min="0"
                            value="<?php echo esc_attr($max_price); ?>"
                            data-ws-filter
                    >

                </div>

            </div>

        </fieldset>
        <?php
    }

    /**
     * Render an attribute filter.
     *
     * @param array $attribute Attribute data.
     * @return void
     */
    protected function render_attribute_filter(array $attribute): void
    {
        ?>
        <fieldset class="ws-product-filter">

            <legend class="ws-product-filter__title">
                <?php echo esc_html($attribute["label"]); ?>
            </legend>

            <?php foreach ($attribute["terms"] as $term): ?>

                <label class="form-check">

                    <input
                            type="checkbox"
                            class="form-check-input"
                            name="<?php echo esc_attr($attribute["taxonomy"]); ?>[]"
                            value="<?php echo esc_attr($term->slug); ?>"
                            data-ws-filter
                    >

                    <span class="form-check-label">
						<?php echo esc_html($term->name); ?>
					</span>

                </label>

            <?php endforeach; ?>

        </fieldset>
        <?php
    }

    /**
     * Get minimum catalog price.
     *
     * @return float
     */
    protected function get_min_price(): float
    {
        global $wpdb;

        $price = $wpdb->get_var(
                "
			SELECT MIN( CAST( meta_value AS DECIMAL(20,6) ) )
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_price'
			AND meta_value != ''
			"
        );

        return $price ? (float) $price : 0.0;
    }

    /**
     * Get maximum catalog price.
     *
     * @return float
     */
    protected function get_max_price(): float
    {
        global $wpdb;

        $price = $wpdb->get_var(
                "
			SELECT MAX( CAST( meta_value AS DECIMAL(20,6) ) )
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_price'
			AND meta_value != ''
			"
        );

        return $price ? (float) $price : 0.0;
    }

    /**
     * Handle AJAX product filtering.
     *
     * @return void
     */
    public function filter_products(): void
    {
        check_ajax_referer("wooshop_product_filters", "nonce");

        $args = [
                "post_type" => "product",
                "post_status" => "publish",
                "posts_per_page" =>
                        wc_get_default_products_per_row() *
                        wc_get_default_product_rows_per_page(),
                "paged" => max(1, absint($_POST["paged"] ?? 1)),
        ];

        $tax_query = [
                "relation" => "AND",
        ];

        $product_cat = isset($_POST["product_cat"])
                ? (array) $_POST["product_cat"]
                : [];

        $product_cat = array_map("sanitize_title", $product_cat);

        if (!empty($product_cat)) {
            $tax_query[] = [
                    "taxonomy" => "product_cat",
                    "field" => "slug",
                    "terms" => $product_cat,
            ];
        }

        $attributes = wc_get_attribute_taxonomies();

        foreach ($attributes as $attribute) {
            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);

            if (empty($_POST[$taxonomy])) {
                continue;
            }

            $terms = array_map("sanitize_title", (array) $_POST[$taxonomy]);

            $tax_query[] = [
                    "taxonomy" => $taxonomy,
                    "field" => "slug",
                    "terms" => $terms,
            ];
        }

        if (count($tax_query) > 1) {
            $args["tax_query"] = $tax_query;
        }

        $meta_query = [
                "relation" => "AND",
        ];

        $min_price = isset($_POST["min_price"])
                ? (float) $_POST["min_price"]
                : 0;

        $max_price = isset($_POST["max_price"])
                ? (float) $_POST["max_price"]
                : 0;

        if ($min_price > 0 || $max_price > 0) {
            $meta_query[] = [
                    "key" => "_price",
                    "value" => [
                            $min_price,
                            $max_price > 0 ? $max_price : PHP_INT_MAX,
                    ],
                    "type" => "DECIMAL",
                    "compare" => "BETWEEN",
            ];
        }

        if (count($meta_query) > 1) {
            $args["meta_query"] = $meta_query;
        }

        $query = new WP_Query($args);

        ob_start();

        if ($query->have_posts()) {
            woocommerce_product_loop_start();

            while ($query->have_posts()) {
                $query->the_post();

                wc_get_template_part("content", "product");
            }

            woocommerce_product_loop_end();
        } else {
            wc_get_template("loop/no-products-found.php");
        }

        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success([
                "html" => $html,
                "count" => $query->found_posts,
        ]);
    }
}
