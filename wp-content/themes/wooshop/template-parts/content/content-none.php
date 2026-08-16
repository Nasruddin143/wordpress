<?php
/**
 * Empty Content State
 *
 * Displays a fallback message when no content is available.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="ws-content-none py-5 text-center">

    <header class="page-header mb-3">

        <h1 class="page-title h3">
            <?php esc_html_e( 'Nothing found', 'wooshop' ); ?>
        </h1>

    </header>

    <div class="page-content">

        <p>
            <?php
            esc_html_e(
                'We could not find any content matching your request.',
                'wooshop'
            );
            ?>
        </p>

    </div>

</section>