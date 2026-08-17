<?php
/**
 * WooShop Data Access
 *
 * Provides lightweight, WordPress-native data access methods.
 * This class intentionally avoids direct SQL and WP_Query for
 * simple data retrieval.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use WC_Product;
use WP_Error;
use WP_Post;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight data access service.
 */
class Data {

    /**
     * Local request-level post cache.
     *
     * @var array
     */
    protected array $posts = array();

    /**
     * Local request-level post collection cache.
     *
     * @var array
     */
    protected array $post_collections = array();

    /**
     * Local request-level meta cache.
     *
     * @var array
     */
    protected array $meta = array();

    /**
     * Retrieve a single post object.
     *
     * Uses get_post() instead of creating a WP_Query instance.
     *
     * @param int $post_id Post ID.
     * @return WP_Post|null
     */
    public function get_post(int $post_id ): ?WP_Post
    {

        $post_id = absint( $post_id );

        if ( ! $post_id ) {
            return null;
        }

        if ( array_key_exists( $post_id, $this->posts ) ) {
            return $this->posts[ $post_id ];
        }

        $this->posts[ $post_id ] = get_post( $post_id );

        return $this->posts[ $post_id ];
    }

    /**
     * Retrieve multiple posts using WordPress get_posts().
     *
     * This is the default collection method for WooShop.
     * WP_Query should only be used for advanced query requirements.
     *
     * @param array $args get_posts() arguments.
     * @return array
     */
    public function get_posts( array $args = array() ): array
    {

        $defaults = array(
            'post_type'              => 'post',
            'posts_per_page'         => 10,
            'no_found_rows'          => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        );

        $args = wp_parse_args( $args, $defaults );

        $key = md5( wp_json_encode( $args ) );

        if ( isset( $this->post_collections[ $key ] ) ) {
            return $this->post_collections[ $key ];
        }

        $this->post_collections[ $key ] = get_posts( $args );

        return $this->post_collections[ $key ];
    }

    /**
     * Retrieve post meta.
     *
     * Values are cached locally for the current request.
     *
     * @param int $post_id Post ID.
     * @param string $key     Meta key.
     * @param bool $single  Whether to return a single value.
     * @return mixed
     */
    public function get_post_meta(int $post_id, string $key = '', bool $single = false ): mixed
    {

        $post_id = absint( $post_id );

        if ( ! $post_id ) {
            return $single ? '' : array();
        }

        $cache_key = $post_id . ':' . $key . ':' . ( $single ? 'single' : 'multiple' );

        if ( array_key_exists( $cache_key, $this->meta ) ) {
            return $this->meta[ $cache_key ];
        }

        $this->meta[ $cache_key ] = get_post_meta(
            $post_id,
            $key,
            $single
        );

        return $this->meta[ $cache_key ];
    }

    /**
     * Retrieve a single term.
     *
     * Uses get_term() instead of a custom database query.
     *
     * @param int $term_id  Term ID.
     * @param string $taxonomy Taxonomy name.
     * @return WP_Term|WP_Error
     */
    public function get_term(int $term_id, string $taxonomy ): WP_Term|WP_Error
    {

        $term_id = absint( $term_id );

        if ( ! $term_id || ! $taxonomy ) {
            return new WP_Error(
                'wooshop_invalid_term',
                __( 'Invalid term.', 'wooshop' )
            );
        }

        return get_term( $term_id, $taxonomy );
    }

    /**
     * Retrieve terms using the native WordPress API.
     *
     * @param array $args get_terms() arguments.
     * @return array|WP_Error
     */
    public function get_terms( array $args = array() ): WP_Error|array
    {

        return get_terms( $args );
    }

    /**
     * Retrieve user meta.
     *
     * @param int $user_id User ID.
     * @param string $key     Meta key.
     * @param bool $single  Whether to return a single value.
     * @return mixed
     */
    public function get_user_meta(int $user_id, string $key = '', bool $single = false ): mixed
    {

        $user_id = absint( $user_id );

        if ( ! $user_id ) {
            return $single ? '' : array();
        }

        return get_user_meta(
            $user_id,
            $key,
            $single
        );
    }

    /**
     * Retrieve an option using the WordPress options API.
     *
     * @param string $option  Option name.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function get_option(string $option, mixed $default = false ): mixed
    {

        return get_option( $option, $default );
    }

    /**
     * Retrieve a WooCommerce product.
     *
     * Uses WooCommerce's CRUD layer instead of direct SQL.
     *
     * @param WC_Product|int $product Product ID or object.
     * @return WC_Product|null
     */
    public function get_product(WC_Product|int $product ): ?WC_Product
    {

        if ( $product instanceof WC_Product ) {
            return $product;
        }

        $product_id = absint( $product );

        if ( ! $product_id || ! function_exists( 'wc_get_product' ) ) {
            return null;
        }

        $product = wc_get_product( $product_id );

        return $product instanceof WC_Product ? $product : null;
    }

    /**
     * Retrieve the current queried object.
     *
     * This does not create a new query.
     *
     * @return object|null
     */
    public function get_queried_object(): ?object
    {

        $object = get_queried_object();

        return is_object( $object ) ? $object : null;
    }
}