<?php
/**
 * Site Branding.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="site-branding flex-shrink-0">

    <h1 class="navbar-brand mb-0">
        <?php if (has_custom_logo()):            the_custom_logo();        else: ?>
    </h1>

    <?php if (is_front_page() && is_home()): ?>

        <h1 class="site-title h3 mb-0">
            <a class="text-decoration-none" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php bloginfo('name'); ?>
            </a>
        </h1>

    <?php else: ?>

        <p class="site-title h3 mb-0">
            <a class="text-decoration-none" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php bloginfo('name'); ?>
            </a>
        </p>

    <?php endif; ?>

    <?php
    $description = get_bloginfo('description', 'display');

    if ($description || is_customize_preview()):
        ?>

        <p class="site-description mb-0 mt-1 text-body-secondary">
            <?php echo esc_html($description); ?>
        </p>

    <?php endif; ?>

    <?php endif; ?>

</div>