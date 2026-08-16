<?php
/**
 * WooShop Social Sharing
 *
 * Adds lightweight social sharing controls to WooCommerce
 * single-product pages without loading third-party SDKs.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * SocialSharing class.
 */
class SocialSharing extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

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
            'woocommerce_single_product_summary',
            array( $this, 'render_sharing' ),
            45
        );
    }

    /**
     * Enqueue social-sharing assets only on product pages.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        $this->assets->load_component(
            'social-sharing'
        );
    }

    /**
     * Render product sharing controls.
     *
     * @return void
     */
    public function render_sharing(): void {

        global $product;

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        $url = get_permalink(
            $product->get_id()
        );

        $title = $product->get_name();

        $encoded_url = rawurlencode(
            $url
        );

        $encoded_title = rawurlencode(
            $title
        );

        $whatsapp_url = sprintf(
            'https://wa.me/?text=%s',
            rawurlencode(
                $title . ' ' . $url
            )
        );

        $facebook_url = sprintf(
            'https://www.facebook.com/sharer/sharer.php?u=%s',
            $encoded_url
        );

        $x_url = sprintf(
            'https://twitter.com/intent/tweet?url=%s&text=%s',
            $encoded_url,
            $encoded_title
        );

        $telegram_url = sprintf(
            'https://t.me/share/url?url=%s&text=%s',
            $encoded_url,
            $encoded_title
        );

        ?>

        <div
            class="ws-social-sharing"
            data-ws-social-sharing
        >

			<span class="ws-social-sharing__label">
				<?php
                esc_html_e(
                    'Share',
                    'wooshop'
                );
                ?>
			</span>

            <div class="ws-social-sharing__actions">

                <a
                    class="ws-social-sharing__link"
                    href="<?php echo esc_url(
                        $whatsapp_url
                    ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php esc_attr_e(
                        'Share on WhatsApp',
                        'wooshop'
                    ); ?>"
                >
                    <span aria-hidden="true">WA</span>
                </a>

                <a
                    class="ws-social-sharing__link"
                    href="<?php echo esc_url(
                        $facebook_url
                    ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php esc_attr_e(
                        'Share on Facebook',
                        'wooshop'
                    ); ?>"
                >
                    <span aria-hidden="true">f</span>
                </a>

                <a
                    class="ws-social-sharing__link"
                    href="<?php echo esc_url(
                        $x_url
                    ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php esc_attr_e(
                        'Share on X',
                        'wooshop'
                    ); ?>"
                >
                    <span aria-hidden="true">X</span>
                </a>

                <a
                    class="ws-social-sharing__link"
                    href="<?php echo esc_url(
                        $telegram_url
                    ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php esc_attr_e(
                        'Share on Telegram',
                        'wooshop'
                    ); ?>"
                >
                    <span aria-hidden="true">TG</span>
                </a>

                <button
                    type="button"
                    class="ws-social-sharing__link"
                    data-ws-copy-link
                    data-share-url="<?php echo esc_attr(
                        $url
                    ); ?>"
                    aria-label="<?php esc_attr_e(
                        'Copy product link',
                        'wooshop'
                    ); ?>"
                >
                    <span aria-hidden="true">↗</span>
                </button>

            </div>

            <span
                class="ws-social-sharing__message"
                data-ws-share-message
                role="status"
                aria-live="polite"
            ></span>

        </div>

        <?php
    }
}