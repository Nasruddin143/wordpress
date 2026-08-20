<?php
/**
 * Page Layout
 *
 * Provides the shared Bootstrap layout for standard pages.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-page-layout">

    <div class="container">

        <div class="row g-4">

            <main class="col-12 ws-content-area">

                <?php
                get_template_part(
                    'template-parts/layout/content-area'
                );
                ?>

            </main>

        </div>

    </div>

</div>