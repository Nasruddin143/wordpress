<?php
/**
 * Main Site Header
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>
<?php do_action( 'wooshop_header_before' ); ?>
    <header
            id="site-header"
            class="ws-header"
            role="banner"
    >

        <div class="container">

            <div class="row align-items-center g-3">

                <div class="col-auto">

                    <?php do_action('wooshop_header_branding'); ?>

                </div>

                <div class="col">

                    <?php do_action('wooshop_header_search'); ?>

                </div>

                <div class="col-auto">

                    <?php do_action('wooshop_header_actions'); ?>

                </div>

            </div>

        </div>

    </header>

<?php do_action( 'wooshop_header_after' ); ?>

<?php do_action('wooshop_header_navigation'); ?>

<?php do_action('wooshop_header_announcement'); ?>


