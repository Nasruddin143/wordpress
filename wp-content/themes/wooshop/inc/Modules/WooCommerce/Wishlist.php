<?php
/**
 * WooCommerce Wishlist Module.
 *
 * Provides wishlist storage and functionality
 * for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Wishlist module.
 */
final class Wishlist extends Module
{
    /**
     * User meta key.
     *
     * @var string
     */
    private const USER_META_KEY = '_wooshop_wishlist';

    /**
     * WooCommerce session key.
     *
     * @var string
     */
    private const SESSION_KEY = 'wooshop_wishlist';

    /**
     * AJAX nonce action.
     *
     * @var string
     */
    private const NONCE_ACTION = 'wooshop_wishlist';

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        if (!$this->is_available()) {
            return;
        }

        add_filter(
            'wooshop_wishlist_url',
            array($this, 'get_wishlist_url')
        );

        add_action(
            'wp_ajax_wooshop_toggle_wishlist',
            array($this, 'toggle_wishlist')
        );

        add_action(
            'wp_ajax_nopriv_wooshop_toggle_wishlist',
            array($this, 'toggle_wishlist')
        );
    }

    /**
     * Check WooCommerce availability.
     *
     * @return bool
     */
    private function is_available(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Get wishlist URL.
     *
     * A dedicated wishlist page will be connected later.
     *
     * @param string $url Existing URL.
     *
     * @return string
     */
    public function get_wishlist_url(string $url): string
    {
        return $url;
    }

    /**
     * Get wishlist product IDs.
     *
     * @return array<int, int>
     */
    public function get_items(): array
    {
        if (is_user_logged_in()) {
            return $this->get_user_items(
                get_current_user_id()
            );
        }

        return $this->get_session_items();
    }

    /**
     * Get wishlist items for a user.
     *
     * @param int $user_id User ID.
     *
     * @return array<int, int>
     */
    private function get_user_items(int $user_id): array
    {
        $items = get_user_meta(
            $user_id,
            self::USER_META_KEY,
            true
        );

        if (!is_array($items)) {
            return [];
        }

        return $this->sanitize_items($items);
    }

    /**
     * Get wishlist items from WooCommerce session.
     *
     * @return array<int, int>
     */
    private function get_session_items(): array
    {
        if (
            !function_exists('WC') ||
            !WC()->session
        ) {
            return [];
        }

        $items = WC()->session->get(
            self::SESSION_KEY,
            []
        );

        if (!is_array($items)) {
            return [];
        }

        return $this->sanitize_items($items);
    }

    /**
     * Add product to wishlist.
     *
     * @param int $product_id Product ID.
     *
     * @return bool
     */
    public function add_item(int $product_id): bool
    {
        if (!$this->is_valid_product($product_id)) {
            return false;
        }

        $items = $this->get_items();

        if (in_array($product_id, $items, true)) {
            return true;
        }

        $items[] = $product_id;

        return $this->save_items($items);
    }

    /**
     * Remove product from wishlist.
     *
     * @param int $product_id Product ID.
     *
     * @return bool
     */
    public function remove_item(int $product_id): bool
    {
        if ($product_id <= 0) {
            return false;
        }

        $items = array_filter(
            $this->get_items(),
            static fn(int $item_id): bool => $item_id !== $product_id
        );

        return $this->save_items(
            array_values($items)
        );
    }

    /**
     * Check whether a product exists in wishlist.
     *
     * @param int $product_id Product ID.
     *
     * @return bool
     */
    public function has_item(int $product_id): bool
    {
        return in_array(
            $product_id,
            $this->get_items(),
            true
        );
    }

    /**
     * Get wishlist item count.
     *
     * @return int
     */
    public function get_count(): int
    {
        return count($this->get_items());
    }

    /**
     * Toggle product wishlist state.
     *
     * @return void
     */
    public function toggle_wishlist(): void
    {
        check_ajax_referer(
            self::NONCE_ACTION,
            'nonce'
        );

        $product_id = isset($_POST['product_id'])
            ? absint($_POST['product_id'])
            : 0;

        if (!$this->is_valid_product($product_id)) {
            wp_send_json_error(
                array(
                    'message' => __(
                        'Invalid product.',
                        'wooshop'
                    ),
                ),
                400
            );
        }

        $added = false;

        if ($this->has_item($product_id)) {
            $success = $this->remove_item($product_id);
        } else {
            $success = $this->add_item($product_id);
            $added = true;
        }

        if (!$success) {
            wp_send_json_error(
                array(
                    'message' => __(
                        'Unable to update wishlist.',
                        'wooshop'
                    ),
                ),
                500
            );
        }

        wp_send_json_success(
            array(
                'product_id' => $product_id,
                'added' => $added,
                'count' => $this->get_count(),
                'message' => $added
                    ? __(
                        'Product added to wishlist.',
                        'wooshop'
                    )
                    : __(
                        'Product removed from wishlist.',
                        'wooshop'
                    ),
            )
        );
    }

    /**
     * Check whether product is valid.
     *
     * @param int $product_id Product ID.
     *
     * @return bool
     */
    private function is_valid_product(int $product_id): bool
    {
        if ($product_id <= 0) {
            return false;
        }

        return (bool)wc_get_product($product_id);
    }

    /**
     * Save wishlist items.
     *
     * @param array<int, int> $items Product IDs.
     *
     * @return bool
     */
    private function save_items(array $items): bool
    {
        $items = $this->sanitize_items($items);

        if (is_user_logged_in()) {
            return false !== update_user_meta(
                    get_current_user_id(),
                    self::USER_META_KEY,
                    $items
                );
        }

        if (
            !function_exists('WC') ||
            !WC()->session
        ) {
            return false;
        }

        WC()->session->set(
            self::SESSION_KEY,
            $items
        );

        return true;
    }

    /**
     * Sanitize wishlist items.
     *
     * @param array $items Wishlist items.
     *
     * @return array<int, int>
     */
    private function sanitize_items(array $items): array
    {
        $items = array_map(
            'absint',
            $items
        );

        $items = array_filter(
            $items
        );

        return array_values(
            array_unique($items)
        );
    }
}
