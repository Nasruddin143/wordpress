<?php
/**
 * Theme Footer
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<?php get_template_part( 'template-parts/global/back-to-top' ); ?>

<?php do_action( 'wooshop_before_footer' ); ?>

<?php do_action( 'wooshop_footer' ); ?>

<?php do_action( 'wooshop_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>