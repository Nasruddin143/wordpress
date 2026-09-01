<?php
/**
 * The template for displaying the footer.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit; ?>


<?php
/**
 * Fires inside the site footer.
 *
 * Hooked by Footer::render_footer() → loads
 * template-parts/footer/footer.php.
 *
 * @hooked WooShop\Modules\Theme\Footer::render_footer — 10
 */
do_action('wooshop_footer'); ?>


</div><!-- #page .site -->

<?php
/**
 * Footer schema JSON-LD and MiniCart drawer render here.
 *
 * @hooked WooShop\Modules\Theme\Footer::schema_markup          — 99
 * @hooked WooShop\Modules\WooCommerce\MiniCart::render_drawer  — 5
 */
wp_footer();
?>

</body>
</html>