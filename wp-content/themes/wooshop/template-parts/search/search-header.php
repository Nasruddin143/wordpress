<?php
/**
 * Search Header
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$query = get_search_query();
$count = isset( $GLOBALS['wp_query']->found_posts )
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
?>

<header class="ws-search-header">

    <h1 class="ws-search-title">

        <?php
        esc_html_e(
            'Search Results for:',
            'wooshop'
        );
        ?>

        <span class="ws-search-query">
            <?php echo esc_html( $query ); ?>
        </span>

    </h1>

    <?php if ( $count > 0 ) : ?>

        <p class="ws-search-count">

            <?php
            printf(
                esc_html(
                    _n(
                        '%d result found',
                        '%d results found',
                        $count,
                        'wooshop'
                    )
                ),
                $count
            );
            ?>

        </p>

    <?php endif; ?>

</header>