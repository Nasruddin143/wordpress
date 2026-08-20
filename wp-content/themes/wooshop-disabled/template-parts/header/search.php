<?php
/**
 * Header Search
 *
 * Displays the primary site search form.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-search">

    <form
        role="search"
        method="get"
        class="search-form"
        action="<?php echo esc_url( home_url( '/' ) ); ?>"
    >

        <label class="w-100">

			<span class="visually-hidden">
				<?php esc_html_e( 'Search for:', 'wooshop' ); ?>
			</span>

            <div class="input-group">

                <input
                    type="search"
                    class="form-control"
                    name="s"
                    value="<?php echo esc_attr( get_search_query() ); ?>"
                    placeholder="<?php esc_attr_e( 'Search products...', 'wooshop' ); ?>"
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                    aria-label="<?php esc_attr_e( 'Submit search', 'wooshop' ); ?>"
                >
                    <?php esc_html_e( 'Search', 'wooshop' ); ?>
                </button>

            </div>

        </label>

    </form>

</div>