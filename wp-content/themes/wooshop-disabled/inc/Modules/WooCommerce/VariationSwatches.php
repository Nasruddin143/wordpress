<?php
/**
 * WooShop Variation Swatches
 *
 * Converts selected WooCommerce variation attributes into
 * accessible visual swatches while preserving WooCommerce's
 * native variation-selection system.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * VariationSwatches class.
 */
class VariationSwatches extends Module {

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
    public function __construct(
        Container $container,
        AssetsManager $assets
    ) {

        parent::__construct( $container );

        $this->assets = $assets;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_assets' ),
            30
        );

        add_filter(
            'woocommerce_dropdown_variation_attribute_options_html',
            array( $this, 'render_swatches' ),
            20,
            2
        );
    }

    /**
     * Enqueue variation swatch assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! $this->is_product_page() ) {
            return;
        }

        $this->assets->load_component(
            'variation-swatches'
        );
    }

    /**
     * Determine whether the current page is a product page.
     *
     * @return bool
     */
    protected function is_product_page(): bool {

        return class_exists( 'WooCommerce' )
            && is_product();
    }

    /**
     * Replace WooCommerce variation dropdown with swatches.
     *
     * The original select remains in the DOM but is visually hidden.
     * JavaScript continues to use it as the source of truth.
     *
     * @param string $html      Variation dropdown HTML.
     * @param array  $args      Variation attribute arguments.
     * @return string
     */
    public function render_swatches(
        string $html,
        array $args
    ): string {

        if ( empty( $args['options'] ) ) {
            return $html;
        }

        if ( empty( $args['attribute'] ) ) {
            return $html;
        }

        $attribute = sanitize_title(
            $args['attribute']
        );

        $options = $args['options'];

        $taxonomy = '';

        if ( taxonomy_exists( $attribute ) ) {
            $taxonomy = $attribute;
        } elseif (
            0 === strpos(
                $attribute,
                'pa_'
            )
        ) {
            $taxonomy = $attribute;
        }

        $terms = array();

        if ( $taxonomy ) {

            $terms = get_terms(
                array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'slug'       => $options,
                )
            );

            if ( is_wp_error( $terms ) ) {
                $terms = array();
            }
        }

        ob_start();

        ?>

        <div
            class="ws-variation-swatches"
            data-ws-swatches
            data-attribute="<?php echo esc_attr( $attribute ); ?>"
        >

            <?php
            /*
             * Keep the original WooCommerce select.
             * The JS layer synchronizes swatches with it.
             */
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>

            <div
                class="ws-variation-swatches__options"
                role="group"
                aria-label="<?php echo esc_attr(
                    $this->get_attribute_label(
                        $attribute
                    )
                ); ?>"
            >

                <?php foreach ( $options as $option ) : ?>

                    <?php
                    $term = $this->find_term(
                        $terms,
                        $option
                    );

                    $label = $term
                        ? $term->name
                        : $option;

                    $value = sanitize_title(
                        $option
                    );
                    ?>

                    <button
                        type="button"
                        class="ws-variation-swatch"
                        data-ws-swatch
                        data-value="<?php echo esc_attr( $value ); ?>"
                        aria-label="<?php echo esc_attr( $label ); ?>"
                        aria-pressed="false"
                    >
						<span class="ws-variation-swatch__label">
							<?php echo esc_html( $label ); ?>
						</span>
                    </button>

                <?php endforeach; ?>

            </div>

        </div>

        <?php

        return ob_get_clean();
    }

    /**
     * Find a term by slug.
     *
     * @param array  $terms Terms.
     * @param string $value Term value.
     * @return object|null
     */
    protected function find_term(
        array $terms,
        string $value
    ) {

        $slug = sanitize_title( $value );

        foreach ( $terms as $term ) {

            if ( $term->slug === $slug ) {
                return $term;
            }
        }

        return null;
    }

    /**
     * Get an attribute label.
     *
     * @param string $attribute Attribute name.
     * @return string
     */
    protected function get_attribute_label(
        string $attribute
    ): string {

        if ( taxonomy_exists( $attribute ) ) {

            $taxonomy = get_taxonomy(
                $attribute
            );

            if ( $taxonomy && ! empty( $taxonomy->labels->singular_name ) ) {

                return $taxonomy->labels->singular_name;
            }
        }

        return wc_attribute_label(
            $attribute
        );
    }
}