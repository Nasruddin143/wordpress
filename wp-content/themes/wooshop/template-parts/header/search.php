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
            role="search"
            method="get"
            class="ws-search__form"
            action="<?php echo esc_url( home_url( '/' ) ); ?>">

        <label
                class="visually-hidden"
                for="ws-header-search">

            <?php esc_html_e( 'Search', 'wooshop' ); ?>

        </label>

        <div class="input-group">

            <input
                    id="ws-header-search"
                    type="search"
                    class="form-control"
                    name="s"
                    value="<?php echo esc_attr( get_search_query() ); ?>"
                    placeholder="<?php esc_attr_e( 'Search products, posts and more...', 'wooshop' ); ?>"
                    autocomplete="off">

            <button
                    type="submit"
                    class="btn btn-primary">

                <span aria-hidden="true">⌕</span>

                <span class="visually-hidden">
                    <?php esc_html_e( 'Submit search', 'wooshop' ); ?>
                </span>

            </button>

        </div>

    </form>

</div>