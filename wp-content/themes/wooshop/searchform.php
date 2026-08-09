<?php
/**
 * Search Form
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<form
    role="search"
    method="get"
    class="ws-search-form"
    action="<?php echo esc_url( home_url( '/' ) ); ?>">

    <label
        class="screen-reader-text"
        for="ws-search-field">

        <?php
        esc_html_e(
            'Search for:',
            'wooshop'
        );
        ?>

    </label>

    <div class="ws-search-form__field">

        <input
            type="search"
            id="ws-search-field"
            class="ws-search-form__input"
            name="s"
            value="<?php echo esc_attr( get_search_query() ); ?>"
            placeholder="<?php esc_attr_e( 'Search products, posts, and more…', 'wooshop' ); ?>"
            autocomplete="off">

        <button
            type="submit"
            class="ws-search-form__submit">

            <?php
            esc_html_e(
                'Search',
                'wooshop'
            );
            ?>

        </button>

    </div>

</form>