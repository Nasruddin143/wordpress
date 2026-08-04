<?php
/**
 * Header Search
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-search">

    <form
        class="ws-search__form"
        action="<?php echo esc_url( home_url( '/' ) ); ?>"
        method="get"
        role="search"
    >

        <div class="input-group">

            <?php wooshop_product_category_dropdown(
                    'product_cat',
                    sanitize_text_field( wp_unslash( $_GET['product_cat'] ?? '' ) )
            ); ?>

            <input
                type="search"
                class="form-control ws-search__input"
                name="s"
                value="<?php echo esc_attr( get_search_query() ); ?>"
                placeholder="<?php esc_attr_e( 'Search products…', 'wooshop' ); ?>"
                aria-label="<?php esc_attr_e( 'Search', 'wooshop' ); ?>"
                autocomplete="off"
            />

            <input
                type="hidden"
                name="post_type"
                value="product"
            />

            <button
                class="btn btn-primary ws-search__button"
                type="submit"
                aria-label="<?php esc_attr_e( 'Search', 'wooshop' ); ?>"
            >
                <span class="dashicons dashicons-search"></span>
            </button>

        </div>

    </form>

</div>