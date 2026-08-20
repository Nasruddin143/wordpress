<?php
/**
 * WooShop Product Videos
 *
 * Adds product video URLs to WooCommerce products and
 * renders them on the single-product page.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * ProductVideos class.
 */
class ProductVideos extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Product video meta key.
     *
     * @var string
     */
    protected string $meta_key = '_wooshop_product_videos';

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
            array( $this, 'render_videos' ),
            20
        );
    }

    /**
     * Enqueue product-video assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        if ( empty( $this->get_videos() ) ) {
            return;
        }

        $this->assets->load_component(
            'product-videos'
        );
    }

    /**
     * Render product video fields.
     *
     * @return void
     */
    public function render_product_fields(): void {

        global $post;

        $videos = get_post_meta(
            $post->ID,
            $this->meta_key,
            true
        );

        $videos = is_array( $videos )
            ? $videos
            : array();

        ?>

        <div class="options_group">

            <p class="form-field">
                <label>
                    <?php
                    esc_html_e(
                        'Product Videos',
                        'wooshop'
                    );
                    ?>
                </label>

                <span class="description">
					<?php
                    esc_html_e(
                        'Add YouTube, Vimeo, or self-hosted video URLs.',
                        'wooshop'
                    );
                    ?>
				</span>
            </p>

            <div
                id="wooshop-product-videos"
                class="wc-metaboxes"
            >

                <?php foreach ( $videos as $index => $video ) : ?>

                    <?php
                    $this->render_video_field(
                        $index,
                        $video
                    );
                    ?>

                <?php endforeach; ?>

            </div>

            <p class="form-field">

                <button
                    type="button"
                    class="button"
                    data-wooshop-add-video
                >
                    <?php
                    esc_html_e(
                        'Add Product Video',
                        'wooshop'
                    );
                    ?>
                </button>

            </p>

        </div>

        <script
            type="text/template"
            id="wooshop-video-template"
        >
            <?php
            $this->render_video_field(
                '__INDEX__',
                array()
            );
            ?>
        </script>

        <?php
    }

    /**
     * Render a video editor field.
     *
     * @param int|string $index Video index.
     * @param array       $video Video data.
     * @return void
     */
    protected function render_video_field(
        $index,
        array $video
    ): void {

        $title = isset( $video['title'] )
            ? $video['title']
            : '';

        $url = isset( $video['url'] )
            ? $video['url']
            : '';

        ?>

        <div
            class="wooshop-product-video wc-metabox"
            data-wooshop-video
        >

            <div class="wc-metabox-title">

                <strong>
                    <?php
                    esc_html_e(
                        'Product Video',
                        'wooshop'
                    );
                    ?>
                </strong>

                <button
                    type="button"
                    class="button-link"
                    data-wooshop-remove-video
                    aria-label="<?php esc_attr_e(
                        'Remove video',
                        'wooshop'
                    ); ?>"
                >
                    &times;
                </button>

            </div>

            <div class="wc-metabox-content">

                <p class="form-field">

                    <label>
                        <?php
                        esc_html_e(
                            'Video Title',
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
                            'Video URL',
                            'wooshop'
                        );
                        ?>
                    </label>

                    <input
                        type="url"
                        name="<?php echo esc_attr(
                            $this->meta_key
                        ); ?>[<?php echo esc_attr(
                            $index
                        ); ?>][url]"
                        value="<?php echo esc_url(
                            $url
                        ); ?>"
                        placeholder="https://"
                    >

                </p>

            </div>

        </div>

        <?php
    }

    /**
     * Save product video fields.
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

        $videos = isset(
            $_POST[ $this->meta_key ]
        )
            ? wp_unslash(
                $_POST[ $this->meta_key ]
            )
            : array();

        if ( ! is_array( $videos ) ) {

            delete_post_meta(
                $post_id,
                $this->meta_key
            );

            return;
        }

        $clean_videos = array();

        foreach ( $videos as $video ) {

            if ( ! is_array( $video ) ) {
                continue;
            }

            $title = isset( $video['title'] )
                ? sanitize_text_field(
                    $video['title']
                )
                : '';

            $url = isset( $video['url'] )
                ? esc_url_raw(
                    $video['url']
                )
                : '';

            if ( empty( $url ) ) {
                continue;
            }

            $clean_videos[] = array(
                'title' => $title,
                'url'   => $url,
            );
        }

        if ( empty( $clean_videos ) ) {

            delete_post_meta(
                $post_id,
                $this->meta_key
            );

            return;
        }

        update_post_meta(
            $post_id,
            $this->meta_key,
            $clean_videos
        );
    }

    /**
     * Get product videos.
     *
     * @return array
     */
    protected function get_videos(): array {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return array();
        }

        $videos = get_post_meta(
            $product->get_id(),
            $this->meta_key,
            true
        );

        return is_array( $videos )
            ? $videos
            : array();
    }

    /**
     * Render product videos.
     *
     * @return void
     */
    public function render_videos(): void {

        $videos = $this->get_videos();

        if ( empty( $videos ) ) {
            return;
        }

        ?>

        <section
            class="ws-product-videos"
            aria-labelledby="ws-product-videos-title"
        >

            <div class="container">

                <h2
                    id="ws-product-videos-title"
                    class="h4 ws-product-videos__title"
                >
                    <?php
                    esc_html_e(
                        'Product Videos',
                        'wooshop'
                    );
                    ?>
                </h2>

                <div class="ws-product-videos__grid">

                    <?php foreach ( $videos as $video ) : ?>

                        <?php
                        $title = isset(
                            $video['title']
                        )
                            ? $video['title']
                            : '';

                        $url = isset(
                            $video['url']
                        )
                            ? $video['url']
                            : '';

                        $embed = wp_oembed_get(
                            $url
                        );
                        ?>

                        <article
                            class="ws-product-videos__item"
                        >

                            <?php if ( $embed ) : ?>

                                <div
                                    class="ws-product-videos__embed"
                                >
                                    <?php
                                    echo wp_kses(
                                        $embed,
                                        array(
                                            'iframe' => array(
                                                'src'             => true,
                                                'width'           => true,
                                                'height'          => true,
                                                'frameborder'     => true,
                                                'allow'           => true,
                                                'allowfullscreen' => true,
                                                'title'           => true,
                                                'loading'         => true,
                                            ),
                                        )
                                    );
                                    ?>
                                </div>

                            <?php else : ?>

                                <video
                                    class="ws-product-videos__player"
                                    controls
                                    playsinline
                                    preload="metadata"
                                >
                                    <source
                                        src="<?php echo esc_url(
                                            $url
                                        ); ?>"
                                    >
                                </video>

                            <?php endif; ?>

                            <?php if ( $title ) : ?>

                                <h3 class="h6 mt-2 mb-0">
                                    <?php
                                    echo esc_html(
                                        $title
                                    );
                                    ?>
                                </h3>

                            <?php endif; ?>

                        </article>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

        <?php
    }
}