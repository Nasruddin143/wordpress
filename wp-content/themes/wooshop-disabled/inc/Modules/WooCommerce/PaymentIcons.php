<?php
/**
 * WooShop Payment Icons
 *
 * Displays available payment-method icons on WooCommerce
 * storefront areas without loading external payment SDKs.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * PaymentIcons class.
 */
class PaymentIcons extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Customizer setting ID.
     *
     * @var string
     */
    protected string $setting_key = 'wooshop_payment_icons';

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
            'customize_register',
            array( $this, 'register_customizer' )
        );

        add_action(
            'woocommerce_after_add_to_cart_form',
            array( $this, 'render_product_icons' ),
            20
        );

        add_action(
            'wp_footer',
            array( $this, 'render_footer_icons' ),
            20
        );
    }

    /**
     * Enqueue payment-icon assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! $this->is_enabled() ) {
            return;
        }

        $this->assets->load_component(
            'payment-icons'
        );
    }

    /**
     * Register payment-icon Customizer settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager.
     * @return void
     */
    public function register_customizer(
        $wp_customize
    ): void {

        $wp_customize->add_section(
            'wooshop_payment_icons',
            array(
                'title'    => __(
                    'Payment Icons',
                    'wooshop'
                ),
                'priority' => 80,
            )
        );

        $wp_customize->add_setting(
            $this->setting_key,
            array(
                'default'           => $this->get_default_icons(),
                'sanitize_callback' => array(
                    $this,
                    'sanitize_icons',
                ),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            $this->setting_key,
            array(
                'type'        => 'textarea',
                'section'     => 'wooshop_payment_icons',
                'label'       => __(
                    'Payment Icons',
                    'wooshop'
                ),
                'description' => __(
                    'Enter one payment method per line. Use: slug|label',
                    'wooshop'
                ),
            )
        );
    }

    /**
     * Return default payment icons.
     *
     * @return string
     */
    protected function get_default_icons(): string {

        return implode(
            "\n",
            array(
                'visa|Visa',
                'mastercard|Mastercard',
                'rupay|RuPay',
                'upi|UPI',
                'cod|Cash on Delivery',
            )
        );
    }

    /**
     * Sanitize Customizer payment icons.
     *
     * @param string $value Raw value.
     * @return string
     */
    public function sanitize_icons(
        $value
    ): string {

        $lines = preg_split(
            '/\r\n|\r|\n/',
            (string) $value
        );

        $clean = array();

        foreach ( $lines as $line ) {

            $line = trim( $line );

            if ( '' === $line ) {
                continue;
            }

            $parts = array_map(
                'trim',
                explode(
                    '|',
                    $line,
                    2
                )
            );

            $slug = sanitize_key(
                $parts[0]
            );

            $label = isset( $parts[1] )
                ? sanitize_text_field(
                    $parts[1]
                )
                : ucfirst( $slug );

            if ( '' === $slug ) {
                continue;
            }

            $clean[] = $slug . '|' . $label;
        }

        return implode(
            "\n",
            $clean
        );
    }

    /**
     * Determine whether payment icons are enabled.
     *
     * @return bool
     */
    protected function is_enabled(): bool {

        return ! empty(
        get_theme_mod(
            $this->setting_key,
            $this->get_default_icons()
        )
        );
    }

    /**
     * Get configured payment methods.
     *
     * @return array
     */
    protected function get_icons(): array {

        $value = get_theme_mod(
            $this->setting_key,
            $this->get_default_icons()
        );

        $lines = preg_split(
            '/\r\n|\r|\n/',
            $value
        );

        $icons = array();

        foreach ( $lines as $line ) {

            $parts = array_map(
                'trim',
                explode(
                    '|',
                    $line,
                    2
                )
            );

            if (
                empty( $parts[0] )
            ) {
                continue;
            }

            $icons[] = array(
                'slug'  => sanitize_key(
                    $parts[0]
                ),
                'label' => isset( $parts[1] )
                    ? sanitize_text_field(
                        $parts[1]
                    )
                    : ucfirst(
                        $parts[0]
                    ),
            );
        }

        return $icons;
    }

    /**
     * Render payment icons after the add-to-cart form.
     *
     * @return void
     */
    public function render_product_icons(): void {

        if ( ! is_product() ) {
            return;
        }

        $this->render_icons(
            'product'
        );
    }

    /**
     * Render payment icons in the footer.
     *
     * @return void
     */
    public function render_footer_icons(): void {

        if ( is_product() ) {
            return;
        }

        $this->render_icons(
            'footer'
        );
    }

    /**
     * Render payment icons.
     *
     * @param string $context Rendering context.
     * @return void
     */
    protected function render_icons(
        string $context
    ): void {

        $icons = $this->get_icons();

        if ( empty( $icons ) ) {
            return;
        }

        ?>

        <div
            class="ws-payment-icons ws-payment-icons--<?php echo esc_attr(
                $context
            ); ?>"
        >

			<span class="ws-payment-icons__label">
				<?php
                esc_html_e(
                    'Secure payment',
                    'wooshop'
                );
                ?>
			</span>

            <div class="ws-payment-icons__list">

                <?php foreach ( $icons as $icon ) : ?>

                    <span
                        class="ws-payment-icons__item ws-payment-icons__item--<?php echo esc_attr(
                            $icon['slug']
                        ); ?>"
                        title="<?php echo esc_attr(
                            $icon['label']
                        ); ?>"
                    >
						<span
                            class="ws-payment-icons__mark"
                            aria-hidden="true"
                        >
							<?php
                            echo esc_html(
                                $this->get_icon_text(
                                    $icon['slug']
                                )
                            );
                            ?>
						</span>

						<span class="visually-hidden">
							<?php
                            echo esc_html(
                                $icon['label']
                            );
                            ?>
						</span>
					</span>

                <?php endforeach; ?>

            </div>

        </div>

        <?php
    }

    /**
     * Return compact visual text for a payment method.
     *
     * @param string $slug Payment method slug.
     * @return string
     */
    protected function get_icon_text(
        string $slug
    ): string {

        $labels = array(
            'visa'       => 'VISA',
            'mastercard' => 'MC',
            'rupay'      => 'RuPay',
            'upi'        => 'UPI',
            'cod'        => 'COD',
            'paypal'     => 'PP',
            'apple-pay'  => ' Pay',
            'google-pay' => 'G Pay',
        );

        return isset( $labels[ $slug ] )
            ? $labels[ $slug ]
            : strtoupper(
                $slug
            );
    }
}