<?php
/**
 * WooShop Size Guide
 *
 * Provides a responsive product size-guide modal.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * SizeGuide class.
 */
class SizeGuide extends Module
{

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Product meta key.
     *
     * @var string
     */
    protected string $meta_key = '_wooshop_size_guide';

    /**
     * Constructor.
     *
     * @param \WooShop\Core\Container $container Service container.
     * @param AssetsManager $assets Asset manager.
     */
    public function __construct(
            \WooShop\Core\Container $container,
            AssetsManager           $assets
    )
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

        add_action(
                'wp_enqueue_scripts',
                array($this, 'enqueue_assets'),
                30
        );

        add_action(
                'woocommerce_before_add_to_cart_form',
                array($this, 'render_button'),
                20
        );

        add_action(
                'add_meta_boxes',
                array($this, 'add_meta_box')
        );

        add_action(
                'save_post_product',
                array($this, 'save_meta')
        );

        add_action(
                'wp_footer',
                array( $this, 'render_dialog' )
        );
    }

    /**
     * Enqueue Size Guide assets.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {

        if (!is_product()) {
            return;
        }

        if (!$this->has_size_guide()) {
            return;
        }

        $this->assets->load_component(
                'size-guide'
        );
    }

    /**
     * Determine whether the current product has a size guide.
     *
     * @return bool
     */
    protected function has_size_guide(): bool
    {

        global $product;

        if (!$product instanceof \WC_Product) {
            return false;
        }

        return '' !== trim(
                        (string)get_post_meta(
                                $product->get_id(),
                                $this->meta_key,
                                true
                        )
                );
    }

    /**
     * Render Size Guide button.
     *
     * @return void
     */
    public function render_button(): void
    {

        if (!$this->has_size_guide()) {
            return;
        }

        ?>
        <button
                type="button"
                class="btn btn-link ws-size-guide-button"
                data-ws-size-guide-open
                aria-haspopup="dialog"
        >
            <?php esc_html_e('Size Guide', 'wooshop'); ?>
        </button>
        <?php
    }

    /**
     * Add product Size Guide meta box.
     *
     * @return void
     */
    public function add_meta_box(): void
    {

        add_meta_box(
                'wooshop-size-guide',
                __('WooShop Size Guide', 'wooshop'),
                array($this, 'render_meta_box'),
                'product',
                'normal',
                'default'
        );
    }

    /**
     * Render product Size Guide meta box.
     *
     * @param \WP_Post $post Product post.
     * @return void
     */
    public function render_meta_box(
            \WP_Post $post
    ): void
    {

        wp_nonce_field(
                'wooshop_size_guide',
                'wooshop_size_guide_nonce'
        );

        $content = get_post_meta(
                $post->ID,
                $this->meta_key,
                true
        );

        wp_editor(
                $content,
                'wooshop_size_guide',
                array(
                        'textarea_name' => 'wooshop_size_guide',
                        'textarea_rows' => 8,
                        'media_buttons' => false,
                )
        );
    }

    /**
     * Save Size Guide product data.
     *
     * @param int $post_id Product ID.
     * @return void
     */
    public function save_meta(
            int $post_id
    ): void
    {

        if (
                !isset(
                        $_POST['wooshop_size_guide_nonce']
                )
        ) {
            return;
        }

        if (
                !wp_verify_nonce(
                        sanitize_text_field(
                                wp_unslash(
                                        $_POST['wooshop_size_guide_nonce']
                                )
                        ),
                        'wooshop_size_guide'
                )
        ) {
            return;
        }

        if (
                defined('DOING_AUTOSAVE')
                && DOING_AUTOSAVE
        ) {
            return;
        }

        if (
                !current_user_can(
                        'edit_post',
                        $post_id
                )
        ) {
            return;
        }

        $content = isset(
                $_POST['wooshop_size_guide']
        )
                ? wp_kses_post(
                        wp_unslash(
                                $_POST['wooshop_size_guide']
                        )
                )
                : '';

        if ('' === trim($content)) {

            delete_post_meta(
                    $post_id,
                    $this->meta_key
            );

            return;
        }

        update_post_meta(
                $post_id,
                $this->meta_key,
                $content
        );
    }

    /**
     * Render Size Guide dialog.
     *
     * @return void
     */
    public function render_dialog(): void
    {

        if (!$this->has_size_guide()) {
            return;
        }

        global $product;

        $content = get_post_meta(
                $product->get_id(),
                $this->meta_key,
                true
        );

        ?>
        <div
                class="ws-size-guide"
                data-ws-size-guide
                role="dialog"
                aria-modal="true"
                aria-labelledby="ws-size-guide-title"
        >

            <div
                    class="ws-size-guide__backdrop"
                    data-ws-size-guide-close
            ></div>

            <div class="ws-size-guide__dialog">

                <header class="ws-size-guide__header">

                    <h2
                            id="ws-size-guide-title"
                            class="h4 mb-0"
                    >
                        <?php esc_html_e('Size Guide', 'wooshop'); ?>
                    </h2>

                    <button
                            type="button"
                            class="btn-close"
                            data-ws-size-guide-close
                            aria-label="<?php esc_attr_e('Close Size Guide', 'wooshop'); ?>"
                    ></button>

                </header>

                <div class="ws-size-guide__body">
                    <?php
                    echo wp_kses_post(
                            $content
                    );
                    ?>
                </div>

            </div>

        </div>
        <?php
    }
}