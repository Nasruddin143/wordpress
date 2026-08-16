<?php
/**
 * Product Compare Page
 *
 * Displays the WooShop product comparison table.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main
        id="primary"
        class="site-main ws-compare-page"
    >

        <div class="container py-5">

            <header class="mb-4">

                <h1 class="h2">
                    <?php esc_html_e( 'Compare Products', 'wooshop' ); ?>
                </h1>

            </header>

            <div
                class="ws-compare-table"
                data-ws-compare-table
            >

                <div
                    class="ws-compare-table__loading"
                    data-ws-compare-loading
                >
                    <?php esc_html_e( 'Loading products…', 'wooshop' ); ?>
                </div>

                <div
                    class="ws-compare-table__content"
                    data-ws-compare-content
                ></div>

            </div>

        </div>

    </main>

<?php
get_footer();