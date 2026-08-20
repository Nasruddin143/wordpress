<?php
/**
 * WooShop Product Brands
 *
 * Provides frontend product-brand presentation and navigation
 * using the WooCommerce product_brand taxonomy.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WC_Product;
use WooShop\Core\AssetsManager;
use WooShop\Core\Container;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * ProductBrands class.
 */
class ProductBrands extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Brand taxonomy.
     *
     * @var string
     */
    protected string $taxonomy = 'product_brand';

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

        add_action(
            'woocommerce_single_product_summary',
            array( $this, 'render_product_brand' ),
            7
        );

        add_action(
            'woocommerce_archive_description',
            array( $this, 'render_brand_archive' ),
            15
        );
    }

    /**
     * Enqueue Product Brands assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! taxonomy_exists( $this->taxonomy ) ) {
            return;
        }

        if (
            ! is_product()
            && ! is_shop()
            && ! is_product_category()
            && ! is_product_tag()
            && ! is_tax( $this->taxonomy )
        ) {
            return;
        }

        $this->assets->load_component(
            'product-brands'
        );
    }

    /**
     * Render brand on single product.
     *
     * @return void
     */
    public function render_product_brand(): void {

        global $product;

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $brands = get_the_terms(
            $product->get_id(),
            $this->taxonomy
        );

        if (
            empty( $brands )
            || is_wp_error( $brands )
        ) {
            return;
        }

        ?>
        <div class="ws-product-brand">

			<span class="ws-product-brand__label">
				<?php
                esc_html_e(
                    'Brand:',
                    'wooshop'
                );
                ?>
			</span>

            <div class="ws-product-brand__items">

                <?php foreach ( $brands as $brand ) : ?>

                    <a
                        class="ws-product-brand__link"
                        href="<?php echo esc_url(
                            get_term_link( $brand )
                        ); ?>"
                    >
                        <?php
                        echo esc_html(
                            $brand->name
                        );
                        ?>
                    </a>

                <?php endforeach; ?>

            </div>

        </div>
        <?php
    }

    /**
     * Render brands on the shop archive.
     *
     * @return void
     */
    public function render_brand_archive(): void {

        if (
            ! is_shop()
            && ! is_product_category()
            && ! is_product_tag()
        ) {
            return;
        }

        $brands = get_terms(
            array(
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => true,
                'number'     => 20,
                'orderby'    => 'name',
                'order'      => 'ASC',
            )
        );

        if (
            empty( $brands )
            || is_wp_error( $brands )
        ) {
            return;
        }

        ?>
        <section
            class="ws-brand-list"
            aria-label="<?php esc_attr_e(
                'Product brands',
                'wooshop'
            ); ?>"
        >

            <div class="container">

                <div class="ws-brand-list__header">

                    <h2 class="h5 mb-0">
                        <?php
                        esc_html_e(
                            'Shop by Brand',
                            'wooshop'
                        );
                        ?>
                    </h2>

                </div>

                <div class="ws-brand-list__items">

                    <?php foreach ( $brands as $brand ) : ?>

                        <a
                            class="ws-brand-list__item"
                            href="<?php echo esc_url(
                                get_term_link( $brand )
                            ); ?>"
                        >
                            <?php
                            echo esc_html(
                                $brand->name
                            );
                            ?>
                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>
        <?php
    }
}