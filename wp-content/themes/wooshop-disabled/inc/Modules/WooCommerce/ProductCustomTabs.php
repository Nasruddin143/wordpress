<?php
/**
 * WooShop Product Custom Tabs
 *
 * Provides configurable additional tabs for WooCommerce products.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * ProductCustomTabs class.
 */
class ProductCustomTabs extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Product tabs meta key.
     *
     * @var string
     */
    protected string $meta_key = '_wooshop_custom_tabs';

    /**
     * Constructor.
     *
     * @param \WooShop\Core\Container $container Service container.
     * @param AssetsManager             $assets    Asset manager.
     */
    public function __construct(
        \WooShop\Core\Container $container,
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
            'woocommerce_product_options_general_product_data',
            array( $this, 'render_product_fields' )
        );

        add_action(
            'woocommerce_process_product_meta',
            array( $this, 'save_product_fields' )
        );

        add_action(
            'woocommerce_after_single_product_summary',
            array( $this, 'render_tabs' ),
            15
        );
    }

    /**
     * Enqueue custom-tab assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        if ( ! $this->get_tabs() ) {
            return;
        }

        $this->assets->load_component(
            'product-custom-tabs'
        );
    }

    /**
     * Render product editor fields.
     *
     * @return void
     */
    public function render_product_fields(): void {

        global $post;

        $tabs = get_post_meta(
            $post->ID,
            $this->meta_key,
            true
        );

        $tabs = is_array( $tabs )
            ? $tabs
            : array();

        ?>

        <div class="options_group">

            <p class="form-field">
                <label>
                    <?php
                    esc_html_e(
                        'Custom Product Tabs',
                        'wooshop'
                    );
                    ?>
                </label>

                <span class="description">
					<?php
                    esc_html_e(
                        'Add additional information tabs to this product.',
                        'wooshop'
                    );
                    ?>
				</span>
            </p>

            <div
                id="wooshop-custom-tabs"
                class="wc-metaboxes"
            >

                <?php
                foreach ( $tabs as $index => $tab ) {
                    $this->render_tab_field(
                        $index,
                        $tab
                    );
                }
                ?>

            </div>

            <p class="form-field">

                <button
                    type="button"
                    class="button"
                    data-wooshop-add-tab
                >
                    <?php
                    esc_html_e(
                        'Add Product Tab',
                        'wooshop'
                    );
                    ?>
                </button>

            </p>

        </div>

        <script type="text/template" id="wooshop-tab-template">

            <?php
            $this->render_tab_field(
                '__INDEX__',
                array()
            );
            ?>

        </script>

        <?php
    }

    /**
     * Render one product tab editor field.
     *
     * @param int|string $index Tab index.
     * @param array       $tab   Tab data.
     * @return void
     */
    protected function render_tab_field(
        $index,
        array $tab
    ): void {

        $title = isset( $tab['title'] )
            ? $tab['title']
            : '';

        $content = isset( $tab['content'] )
            ? $tab['content']
            : '';

        ?>

        <div
            class="wooshop-custom-tab wc-metabox"
            data-wooshop-tab
        >

            <div class="wc-metabox-title">

                <button
                    type="button"
                    class="button-link"
                    data-wooshop-remove-tab
                    aria-label="<?php esc_attr_e(
                        'Remove tab',
                        'wooshop'
                    ); ?>"
                >
                    &times;
                </button>

                <strong>
                    <?php
                    esc_html_e(
                        'Product Tab',
                        'wooshop'
                    );
                    ?>
                </strong>

            </div>

            <div class="wc-metabox-content">

                <p class="form-field">

                    <label>
                        <?php
                        esc_html_e(
                            'Tab Title',
                            'wooshop'
                        );
                        ?>
                    </label>

                    <input
                        type="text"
                        name="<?php echo esc_attr(
                            $this->meta_key
                        ); ?>[<?php echo esc_attr(
                            $index
                        ); ?>][title]"
                        value="<?php echo esc_attr(
                            $title
                        ); ?>"
                    >

                </p>

                <p class="form-field">

                    <label>
                        <?php
                        esc_html_e(
                            'Tab Content',
                            'wooshop'
                        );
                        ?>
                    </label>

                    <textarea
                        name="<?php echo esc_attr(
                            $this->meta_key
                        ); ?>[<?php echo esc_attr(
                            $index
                        ); ?>][content]"
                        rows="6"
                    ><?php echo esc_textarea(
                            $content
                        ); ?></textarea>

                </p>

            </div>

        </div>

        <?php
    }

    /**
     * Save product custom tabs.
     *
     * @param int $post_id Product ID.
     * @return void
     */
    public function save_product_fields(
        int $post_id
    ): void {

        if (
            ! current_user_can(
                'edit_post',
                $post_id
            )
        ) {
            return;
        }

        $tabs = isset(
            $_POST[ $this->meta_key ]
        )
            ? wp_unslash(
                $_POST[ $this->meta_key ]
            )
            : array();

        if ( ! is_array( $tabs ) ) {
            delete_post_meta(
                $post_id,
                $this->meta_key
            );

            return;
        }

        $clean_tabs = array();

        foreach ( $tabs as $tab ) {

            if ( ! is_array( $tab ) ) {
                continue;
            }

            $title = isset( $tab['title'] )
                ? sanitize_text_field(
                    $tab['title']
                )
                : '';

            $content = isset( $tab['content'] )
                ? wp_kses_post(
                    $tab['content']
                )
                : '';

            if (
                '' === trim( $title )
                || '' === trim( $content )
            ) {
                continue;
            }

            $clean_tabs[] = array(
                'title'   => $title,
                'content' => $content,
            );
        }

        if ( empty( $clean_tabs ) ) {

            delete_post_meta(
                $post_id,
                $this->meta_key
            );

            return;
        }

        update_post_meta(
            $post_id,
            $this->meta_key,
            $clean_tabs
        );
    }

    /**
     * Get current product custom tabs.
     *
     * @return array
     */
    protected function get_tabs(): array {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return array();
        }

        $tabs = get_post_meta(
            $product->get_id(),
            $this->meta_key,
            true
        );

        return is_array( $tabs )
            ? $tabs
            : array();
    }

    /**
     * Render product custom tabs.
     *
     * @return void
     */
    public function render_tabs(): void {

        $tabs = $this->get_tabs();

        if ( empty( $tabs ) ) {
            return;
        }

        ?>
        <section
            class="ws-product-custom-tabs"
            data-ws-custom-tabs
            aria-label="<?php esc_attr_e(
                'Additional product information',
                'wooshop'
            ); ?>"
        >

            <div class="container">

                <div
                    class="ws-product-custom-tabs__nav"
                    role="tablist"
                >

                    <?php foreach ( $tabs as $index => $tab ) : ?>

                        <?php
                        $tab_id = 'ws-product-tab-' . $index;
                        ?>

                        <button
                            type="button"
                            class="ws-product-custom-tabs__button <?php echo 0 === $index ? 'is-active' : ''; ?>"
                            id="<?php echo esc_attr(
                                $tab_id . '-button'
                            ); ?>"
                            role="tab"
                            aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr(
                                $tab_id
                            ); ?>"
                            data-ws-tab-target="<?php echo esc_attr(
                                $tab_id
                            ); ?>"
                        >
                            <?php
                            echo esc_html(
                                $tab['title']
                            );
                            ?>
                        </button>

                    <?php endforeach; ?>

                </div>

                <div class="ws-product-custom-tabs__content">

                    <?php foreach ( $tabs as $index => $tab ) : ?>

                        <?php
                        $tab_id = 'ws-product-tab-' . $index;
                        ?>

                        <div
                            id="<?php echo esc_attr(
                                $tab_id
                            ); ?>"
                            class="ws-product-custom-tabs__panel <?php echo 0 === $index ? 'is-active' : ''; ?>"
                            role="tabpanel"
                            aria-labelledby="<?php echo esc_attr(
                                $tab_id . '-button'
                            ); ?>"
                            <?php echo 0 === $index ? '' : 'hidden'; ?>
                        >
                            <?php
                            echo wp_kses_post(
                                wpautop(
                                    $tab['content']
                                )
                            );
                            ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>
        <?php
    }
}