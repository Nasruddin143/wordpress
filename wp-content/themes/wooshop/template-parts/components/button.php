<?php
/**
 * Button Component
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$text  = $args['text'] ?? '';
$url   = $args['url'] ?? '#';
$class = $args['class'] ?? 'button';
?>

<a
        href="<?php echo esc_url( $url ); ?>"
        class="<?php echo esc_attr( $class ); ?>"
>

    <?php echo esc_html( $text ); ?>

</a>