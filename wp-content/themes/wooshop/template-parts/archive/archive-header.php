<?php
/**
 * Archive Header
 *
 * Displays archive title and description.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$title       = '';
$description = '';

if ( is_search() ) {

    $title = sprintf(
    /* translators: %s: search query. */
        __( 'Search Results for: %s', 'wooshop' ),
        get_search_query()
    );

} else {

    $title       = get_the_archive_title();
    $description = get_the_archive_description();
}
?>

<header class="ws-archive-header">

    <?php if ( $title ) : ?>

        <h1 class="ws-archive-title">

            <?php
            echo wp_kses_post(
                $title
            );
            ?>

        </h1>

    <?php endif; ?>

    <?php if ( $description ) : ?>

        <div class="ws-archive-description">

            <?php
            echo wp_kses_post(
                wpautop( $description )
            );
            ?>

        </div>

    <?php endif; ?>

</header>