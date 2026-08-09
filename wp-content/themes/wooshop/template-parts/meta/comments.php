<?php
/**
 * Comments Meta
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! comments_open() && ! get_comments_number() ) {
    return;
}

$comments_url = get_comments_link();
?>

<span class="ws-meta-comments">

    <a href="<?php echo esc_url( $comments_url ); ?>">

        <?php
        printf(
        /* translators: %s: number of comments. */
            esc_html(
                _n(
                    '%s Comment',
                    '%s Comments',
                    get_comments_number(),
                    'wooshop'
                )
            ),
            esc_html(
                number_format_i18n(
                    get_comments_number()
                )
            )
        );
        ?>

    </a>

</span>