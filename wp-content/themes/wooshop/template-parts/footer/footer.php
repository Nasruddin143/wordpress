<?php
/**
 * Main Footer Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

</div><!-- #content -->

<footer
    id="colophon"
    class="site-footer"
    role="contentinfo"
>

    <div class="container">

        <?php get_template_part( 'template-parts/footer/widgets' ); ?>

        <?php get_template_part( 'template-parts/footer/navigation' ); ?>

        <?php get_template_part( 'template-parts/footer/copyright' ); ?>

        <?php get_template_part( 'template-parts/footer/credits' ); ?>

    </div>

</footer>

<?php get_template_part( 'template-parts/footer/back-to-top' ); ?>

<?php get_template_part( 'template-parts/footer/after-footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>