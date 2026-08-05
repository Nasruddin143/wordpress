<?php
/**
 * Header Branding
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="ws-header-branding">

    <a class="ws-site-branding" href="<?php echo esc_url(home_url('/')); ?>" rel="home">

        <?php if (has_custom_logo()) : ?>

            <div class="ws-site-logo">

                <?php the_custom_logo(); ?>

            </div>

        <?php else : ?>

            <div class="ws-site-identity">

                <span class="ws-site-title">

                    <?php bloginfo('name'); ?>

                </span>

                <?php
                $description = get_bloginfo('description', 'display');

                if ($description) :
                    ?>

                    <span class="ws-site-tagline">

                        <?php echo esc_html($description); ?>

                    </span>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </a>

</div>