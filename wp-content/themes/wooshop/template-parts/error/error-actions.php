<?php
/**
 * Error Actions
 *
 * Provides recovery actions for error pages.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-error-actions">

    <a
        class="ws-button ws-button--primary"
        href="<?php echo esc_url( home_url( '/' ) ); ?>">

        <?php
        esc_html_e(
            'Back to Home',
            'wooshop'
        );
        ?>

    </a>

    <a
        class="ws-button ws-button--secondary"
        href="<?php echo esc_url( wp_get_referer() ?: home_url( '/' ) ); ?>">

        <?php
        esc_html_e(
            'Go Back',
            'wooshop'
        );
        ?>

    </a>

</div>

<div class="ws-error-search">

    <p class="ws-error-search__label">

        <?php
        esc_html_e(
            'Or search our site:',
            'wooshop'
        );
        ?>

    </p>

    <?php
    get_search_form();
    ?>

</div>