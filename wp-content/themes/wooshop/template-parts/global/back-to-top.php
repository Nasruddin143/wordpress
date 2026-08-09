<?php
/**
 * Back To Top
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<button
        class="ws-back-to-top"
        aria-label="<?php esc_attr_e('Back to top', 'wooshop'); ?>">

    <?php
    if (function_exists('wooshop_icon')) {
        echo wooshop_icon('arrow-up');
    } else {
        ?>
        <span aria-hidden="true">↑</span>
        <?php
    }
    ?>

</button>