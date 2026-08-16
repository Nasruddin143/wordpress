<?php
/**
 * WooShop Smart Off-Canvas Mini Cart
 *
 * Provides an AJAX-aware off-canvas shopping cart that
 * integrates with WooCommerce cart fragments.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * MiniCart class.
 */
class MiniCart extends Module {

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
            'wp_footer',
            array( $this, 'render_cart' ),
            5
        );

        add_filter(
            'woocommerce_add_to_cart_fragments',
            array( $this, 'cart_fragments' )
        );
    }

    /**
     * Enqueue mini-cart assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $this->assets->load_component(
            'mini-cart'
        );

        wp_enqueue_script(
            'wc-cart-fragments'
        );
    }

    /**
     * Render the mini-cart markup.
     *
     * @return void
     */
    public function render_cart(): void {

        if ( ! function_exists( 'WC' ) ) {
            return;
        }

        ?>

        <div
            class="ws-mini-cart"
            data-ws-mini-cart
            aria-hidden="true"
        >

            <div
                class="ws-mini-cart__backdrop"
                data-ws-cart-close
            ></div>

            <aside
                class="ws-mini-cart__drawer"
                role="dialog"
                aria-modal="true"
                aria-labelledby="ws-mini-cart-title"
                tabindex="-1"
            >

                <header class="ws-mini-cart__header">

                    <h2
                        id="ws-mini-cart-title"
                        class="ws-mini-cart__title"
                    >
                        <?php
                        esc_html_e(
                            'Your Cart',
                            'wooshop'
                        );
                        ?>

                        <span
                            class="ws-mini-cart__count"
                            data-ws-cart-count
                        >
							<?php
                            echo esc_html(
                                WC()->cart->get_cart_contents_count()
                            );
                            ?>
						</span>
                    </h2>

                    <button
                        type="button"
                        class="ws-mini-cart__close"
                        data-ws-cart-close
                        aria-label="<?php esc_attr_e(
                            'Close cart',
                            'wooshop'
                        ); ?>"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                </header>

                <div
                    class="ws-mini-cart__body"
                    data-ws-cart-body
                >

                    <?php
                    $this->render_cart_content();
                    ?>

                </div>

            </aside>

        </div>

        <?php
    }

    /**
     * Render cart contents.
     *
     * @return void
     */
    protected function render_cart_content(): void {

        ?>

        <div class="ws-mini-cart__items">

            <?php
            if ( WC()->cart->is_empty() ) :
                ?>

                <div class="ws-mini-cart__empty">

                    <div
                        class="ws-mini-cart__empty-icon"
                        aria-hidden="true"
                    >
                        🛒
                    </div>

                    <p>
                        <?php
                        esc_html_e(
                            'Your cart is currently empty.',
                            'wooshop'
                        );
                        ?>
                    </p>

                    <a
                        class="btn btn-primary"
                        href="<?php echo esc_url(
                            wc_get_page_permalink(
                                'shop'
                            )
                        ); ?>"
                    >
                        <?php
                        esc_html_e(
                            'Continue Shopping',
                            'wooshop'
                        );
                        ?>
                    </a>

                </div>

            <?php
            else :
                ?>

                <ul class="ws-mini-cart__list">

                    <?php
                    foreach (
                        WC()->cart->get_cart()
                        as $cart_item_key => $cart_item
                    ) :

                        $product = $cart_item['data'];

                        if (
                            ! $product ||
                            ! $product->exists()
                        ) {
                            continue;
                        }

                        $product_name =
                            $product->get_name();

                        $product_permalink =
                            $product->is_visible()
                                ? $product->get_permalink(
                                $cart_item
                            )
                                : '';

                        $thumbnail =
                            $product->get_image(
                                'woocommerce_thumbnail'
                            );
                        ?>

                        <li
                            class="ws-mini-cart__item"
                            data-cart-item="<?php echo esc_attr(
                                $cart_item_key
                            ); ?>"
                        >

                            <div class="ws-mini-cart__thumbnail">

                                <?php
                                echo wp_kses_post(
                                    $thumbnail
                                );
                                ?>

                            </div>

                            <div class="ws-mini-cart__details">

                                <?php if ( $product_permalink ) : ?>

                                    <a
                                        class="ws-mini-cart__product"
                                        href="<?php echo esc_url(
                                            $product_permalink
                                        ); ?>"
                                    >
                                        <?php
                                        echo esc_html(
                                            $product_name
                                        );
                                        ?>
                                    </a>

                                <?php else : ?>

                                    <span
                                        class="ws-mini-cart__product"
                                    >
										<?php
                                        echo esc_html(
                                            $product_name
                                        );
                                        ?>
									</span>

                                <?php endif; ?>

                                <div class="ws-mini-cart__meta">

									<span>
										<?php
                                        echo esc_html(
                                            $cart_item['quantity']
                                        );
                                        ?>
										&times;
									</span>

                                    <span>
										<?php
                                        echo wp_kses_post(
                                            WC()->cart->get_product_price(
                                                $product
                                            )
                                        );
                                        ?>
									</span>

                                </div>

                                <?php
                                echo wc_get_formatted_cart_item_data(
                                    $cart_item
                                );
                                ?>

                            </div>

                            <a
                                href="<?php echo esc_url(
                                    wc_get_cart_remove_url(
                                        $cart_item_key
                                    )
                                ); ?>"
                                class="ws-mini-cart__remove"
                                data-cart-item-key="<?php echo esc_attr(
                                    $cart_item_key
                                ); ?>"
                                aria-label="<?php echo esc_attr(
                                    sprintf(
                                        __(
                                            'Remove %s',
                                            'wooshop'
                                        ),
                                        $product_name
                                    )
                                ); ?>"
                            >
                                &times;
                            </a>

                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php
            endif;
            ?>

        </div>

        <?php if ( ! WC()->cart->is_empty() ) : ?>

            <div class="ws-mini-cart__summary">

                <div class="ws-mini-cart__subtotal">

					<span>
						<?php
                        esc_html_e(
                            'Subtotal',
                            'wooshop'
                        );
                        ?>
					</span>

                    <strong>
                        <?php
                        echo wp_kses_post(
                            WC()->cart->get_cart_subtotal()
                        );
                        ?>
                    </strong>

                </div>

                <p class="ws-mini-cart__notice">
                    <?php
                    esc_html_e(
                        'Shipping and taxes calculated at checkout.',
                        'wooshop'
                    );
                    ?>
                </p>

                <div class="ws-mini-cart__actions">

                    <a
                        class="btn btn-outline-primary"
                        href="<?php echo esc_url(
                            wc_get_cart_url()
                        ); ?>"
                    >
                        <?php
                        esc_html_e(
                            'View Cart',
                            'wooshop'
                        );
                        ?>
                    </a>

                    <a
                        class="btn btn-primary"
                        href="<?php echo esc_url(
                            wc_get_checkout_url()
                        ); ?>"
                    >
                        <?php
                        esc_html_e(
                            'Checkout',
                            'wooshop'
                        );
                        ?>
                    </a>

                </div>

            </div>

        <?php endif; ?>

        <?php
    }

    /**
     * Refresh mini-cart fragments after WooCommerce cart updates.
     *
     * @param array $fragments Existing fragments.
     * @return array
     */
    public function cart_fragments(
        array $fragments
    ): array {

        ob_start();

        $this->render_cart_content();

        $content = ob_get_clean();

        $fragments[
        '[data-ws-cart-body]'
        ] = $content;

        $fragments[
        '[data-ws-cart-count]'
        ] =
            '<span class="ws-mini-cart__count" data-ws-cart-count>' .
            esc_html(
                WC()->cart->get_cart_contents_count()
            ) .
            '</span>';

        return $fragments;
    }
}