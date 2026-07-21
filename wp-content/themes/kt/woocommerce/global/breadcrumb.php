<?php

defined('ABSPATH') || exit;

if (empty($breadcrumb)) {
    return;
}

$last_key = array_key_last($breadcrumb);
?>

<nav class="woocommerce-breadcrumb-wrapper"
    aria-label="<?php esc_attr_e('Breadcrumb', 'kt'); ?>">

    <ol class="breadcrumb mb-0">

        <?php foreach ($breadcrumb as $key => $crumb) : ?>

            <?php
            $is_last = ($key === $last_key);
            ?>

            <li class="breadcrumb-item<?php echo $is_last ? ' active' : ''; ?>"

                <?php if ($is_last) : ?>
                    aria-current="page"
                <?php endif; ?>>

                <?php if (!empty($crumb[1]) && !$is_last) : ?>

                    <a href="<?php echo esc_url($crumb[1]); ?>">

                        <?php echo esc_html($crumb[0]); ?>

                    </a>

                <?php else : ?>

                    <span>

                        <?php echo esc_html($crumb[0]); ?>

                    </span>

                <?php endif; ?>

            </li>

        <?php endforeach; ?>

    </ol>

</nav>