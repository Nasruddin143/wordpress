<?php
/**
 * Modified Date
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( get_the_time( 'U' ) === get_the_modified_time( 'U' ) ) {
    return;
}
?>

<time
    class="ws-meta-modified"
    datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>">

    <?php
    printf(
    /* translators: %s: modified date. */
        esc_html__(
            'Updated %s',
            'wooshop'
        ),
        esc_html(
            get_the_modified_date()
        )
    );
    ?>

</time>