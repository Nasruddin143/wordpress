<?php
/**
 * Post Date
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<time
    class="ws-meta-date"
    datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">

    <?php
    echo esc_html(
        get_the_date()
    );
    ?>

</time>