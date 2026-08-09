<?php
/**
 * Header Search
 *
 * Displays the site search form.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$placeholder = $placeholder ?? __( 'Search...', 'wooshop' );

$show_button = $show_button ?? true;
?>

<div class="ws-header-search">

    <form
            class="ws-search-form"
            role="search"
            method="get"
            action="<?php echo esc_url( home_url( '/' ) ); ?>">

        <label
                class="screen-reader-text"
                for="ws-search">

            <?php esc_html_e( 'Search for:', 'wooshop' ); ?>

        </label>

        <input
                id="ws-search"
                class="ws-search-input"
                type="search"
                name="s"
                value="<?php echo esc_attr( get_search_query() ); ?>"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
        >

        <?php if ( $show_button ) : ?>

            <button
                    type="submit"
                    class="ws-search-button">

                <?php esc_html_e( 'Search', 'wooshop' ); ?>

            </button>

        <?php endif; ?>

    </form>

</div>