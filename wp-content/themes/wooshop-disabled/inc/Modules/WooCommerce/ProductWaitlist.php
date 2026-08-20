<?php
/**
 * WooShop Product Waitlist
 *
 * Provides a lightweight back-in-stock notification
 * subscription system for unavailable products.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\AssetsManager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * ProductWaitlist class.
 */
class ProductWaitlist extends Module {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Database table name.
     *
     * @var string
     */
    protected string $table;

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

        global $wpdb;

        $this->table = $wpdb->prefix . 'wooshop_waitlist';

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
            array( $this, 'render_form' ),
            31
        );

        add_action(
            'wp_ajax_wooshop_waitlist',
            array( $this, 'handle_subscription' )
        );

        add_action(
            'wp_ajax_nopriv_wooshop_waitlist',
            array( $this, 'handle_subscription' )
        );

        add_action(
            'woocommerce_product_set_stock_status',
            array( $this, 'maybe_notify_waitlist' ),
            20,
            3
        );

        add_action(
            'woocommerce_variation_set_stock_status',
            array( $this, 'maybe_notify_waitlist' ),
            20,
            3
        );

        add_action(
            'admin_menu',
            array( $this, 'register_admin_page' )
        );

        add_action(
            'admin_init',
            array( $this, 'create_table' )
        );
    }

    /**
     * Create the waitlist database table.
     *
     * @return void
     */
    public function create_table(): void {

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        global $wpdb;

        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			product_id bigint(20) unsigned NOT NULL,
			email varchar(190) NOT NULL,
			created_at datetime NOT NULL,
			notified tinyint(1) unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY (id),
			UNIQUE KEY product_email (product_id,email),
			KEY product_id (product_id),
			KEY notified (notified)
		) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta( $sql );
    }

    /**
     * Enqueue waitlist assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {

        if ( ! is_product() ) {
            return;
        }

        global $product;

        if (
            ! $product instanceof \WC_Product
            || $product->is_in_stock()
        ) {
            return;
        }

        $this->assets->load_component(
            'product-waitlist'
        );

        wp_localize_script(
            'wooshop-product-waitlist',
            'WooShopWaitlist',
            array(
                'ajaxUrl' => admin_url(
                    'admin-ajax.php'
                ),
                'nonce'   => wp_create_nonce(
                    'wooshop_waitlist'
                ),
            )
        );
    }

    /**
     * Render the waitlist form.
     *
     * @return void
     */
    public function render_form(): void {

        global $product;

        if (
            ! $product instanceof \WC_Product
            || $product->is_in_stock()
        ) {
            return;
        }

        ?>
        <div
            class="ws-product-waitlist"
            data-ws-waitlist
        >

            <div class="ws-product-waitlist__header">

                <h3 class="ws-product-waitlist__title">
                    <?php
                    esc_html_e(
                        'Notify me when available',
                        'wooshop'
                    );
                    ?>
                </h3>

                <p class="ws-product-waitlist__description">
                    <?php
                    esc_html_e(
                        'Enter your email and we will notify you when this product is back in stock.',
                        'wooshop'
                    );
                    ?>
                </p>

            </div>

            <form
                class="ws-product-waitlist__form"
                data-ws-waitlist-form
            >

                <input
                    type="hidden"
                    name="action"
                    value="wooshop_waitlist"
                >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?php echo esc_attr(
                        $product->get_id()
                    ); ?>"
                >

                <input
                    type="hidden"
                    name="nonce"
                    value="<?php echo esc_attr(
                        wp_create_nonce(
                            'wooshop_waitlist'
                        )
                    ); ?>"
                >

                <label
                    class="visually-hidden"
                    for="ws-waitlist-email"
                >
                    <?php
                    esc_html_e(
                        'Email address',
                        'wooshop'
                    );
                    ?>
                </label>

                <div class="ws-product-waitlist__fields">

                    <input
                        id="ws-waitlist-email"
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="<?php esc_attr_e(
                            'Your email address',
                            'wooshop'
                        ); ?>"
                        autocomplete="email"
                        required
                    >

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <?php
                        esc_html_e(
                            'Notify Me',
                            'wooshop'
                        );
                        ?>
                    </button>

                </div>

                <div
                    class="ws-product-waitlist__message"
                    data-ws-waitlist-message
                    role="status"
                    aria-live="polite"
                ></div>

            </form>

        </div>
        <?php
    }

    /**
     * Handle waitlist subscription.
     *
     * @return void
     */
    public function handle_subscription(): void {

        check_ajax_referer(
            'wooshop_waitlist',
            'nonce'
        );

        $product_id = isset(
            $_POST['product_id']
        )
            ? absint(
                $_POST['product_id']
            )
            : 0;

        $email = isset(
            $_POST['email']
        )
            ? sanitize_email(
                wp_unslash(
                    $_POST['email']
                )
            )
            : '';

        if (
            ! $product_id
            || ! is_email( $email )
        ) {

            wp_send_json_error(
                array(
                    'message' => __(
                        'Please enter a valid email address.',
                        'wooshop'
                    ),
                ),
                400
            );
        }

        $product = wc_get_product(
            $product_id
        );

        if ( ! $product ) {

            wp_send_json_error(
                array(
                    'message' => __(
                        'Product could not be found.',
                        'wooshop'
                    ),
                ),
                404
            );
        }

        if ( $product->is_in_stock() ) {

            wp_send_json_error(
                array(
                    'message' => __(
                        'This product is already back in stock.',
                        'wooshop'
                    ),
                )
            );
        }

        global $wpdb;

        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM {$this->table}
				WHERE product_id = %d
				AND email = %s
				LIMIT 1",
                $product_id,
                $email
            )
        );

        if ( $existing ) {

            wp_send_json_success(
                array(
                    'message' => __(
                        'You are already on the waitlist for this product.',
                        'wooshop'
                    ),
                )
            );
        }

        $inserted = $wpdb->insert(
            $this->table,
            array(
                'product_id' => $product_id,
                'email'      => $email,
                'created_at' => current_time(
                    'mysql'
                ),
            ),
            array(
                '%d',
                '%s',
                '%s',
            )
        );

        if ( false === $inserted ) {

            wp_send_json_error(
                array(
                    'message' => __(
                        'Unable to add you to the waitlist. Please try again.',
                        'wooshop'
                    ),
                ),
                500
            );
        }

        wp_send_json_success(
            array(
                'message' => __(
                    'You have been added to the waitlist.',
                    'wooshop'
                ),
            )
        );
    }

    /**
     * Notify subscribers when stock becomes available.
     *
     * @param int    $product_id Product ID.
     * @param string $status     Stock status.
     * @param object $product    Product object.
     * @return void
     */
    public function maybe_notify_waitlist(
        int $product_id,
        string $status,
        $product
    ): void {

        if ( 'instock' !== $status ) {
            return;
        }

        if ( ! $product instanceof \WC_Product ) {
            return;
        }

        global $wpdb;

        $subscribers = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, email
				FROM {$this->table}
				WHERE product_id = %d
				AND notified = 0",
                $product_id
            )
        );

        if ( empty( $subscribers ) ) {
            return;
        }

        $subject = sprintf(
        /* translators: %s: product name. */
            __(
                '%s is back in stock',
                'wooshop'
            ),
            $product->get_name()
        );

        $message = sprintf(
        /* translators: %s: product name. */
            __(
                'Good news! %s is back in stock.',
                'wooshop'
            ),
            $product->get_name()
        );

        $message .= "\n\n";
        $message .= $product->get_permalink();

        foreach ( $subscribers as $subscriber ) {

            wp_mail(
                $subscriber->email,
                $subject,
                $message
            );

            $wpdb->update(
                $this->table,
                array(
                    'notified' => 1,
                ),
                array(
                    'id' => $subscriber->id,
                ),
                array(
                    '%d',
                ),
                array(
                    '%d',
                )
            );
        }
    }

    /**
     * Register waitlist admin page.
     *
     * @return void
     */
    public function register_admin_page(): void {

        add_submenu_page(
            'woocommerce',
            __( 'Product Waitlist', 'wooshop' ),
            __( 'Product Waitlist', 'wooshop' ),
            'manage_woocommerce',
            'wooshop-waitlist',
            array(
                $this,
                'render_admin_page',
            )
        );
    }

    /**
     * Render waitlist admin page.
     *
     * @return void
     */
    public function render_admin_page(): void {

        global $wpdb;

        $rows = $wpdb->get_results(
            "SELECT *
			FROM {$this->table}
			ORDER BY created_at DESC"
        );

        ?>
        <div class="wrap">

            <h1>
                <?php
                esc_html_e(
                    'Product Waitlist',
                    'wooshop'
                );
                ?>
            </h1>

            <table class="widefat striped">

                <thead>

                <tr>
                    <th>
                        <?php
                        esc_html_e(
                            'Product',
                            'wooshop'
                        );
                        ?>
                    </th>

                    <th>
                        <?php
                        esc_html_e(
                            'Email',
                            'wooshop'
                        );
                        ?>
                    </th>

                    <th>
                        <?php
                        esc_html_e(
                            'Date',
                            'wooshop'
                        );
                        ?>
                    </th>

                    <th>
                        <?php
                        esc_html_e(
                            'Status',
                            'wooshop'
                        );
                        ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if ( empty( $rows ) ) : ?>

                    <tr>
                        <td colspan="4">
                            <?php
                            esc_html_e(
                                'No waitlist subscriptions yet.',
                                'wooshop'
                            );
                            ?>
                        </td>
                    </tr>

                <?php else : ?>

                    <?php foreach ( $rows as $row ) : ?>

                        <?php
                        $product = wc_get_product(
                            $row->product_id
                        );
                        ?>

                        <tr>

                            <td>
                                <?php
                                echo $product
                                    ? esc_html(
                                        $product->get_name()
                                    )
                                    : esc_html__(
                                        'Deleted product',
                                        'wooshop'
                                    );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $row->email
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $row->created_at
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $row->notified
                                    ? esc_html__(
                                        'Notified',
                                        'wooshop'
                                    )
                                    : esc_html__(
                                        'Waiting',
                                        'wooshop'
                                    );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>
        <?php
    }
}