<?php
/**
 * Archive Layout
 *
 * Provides the shared responsive layout for archive pages.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-archive-layout">

    <div class="container">

        <div class="row g-4">

            <main class="col-12 col-lg-8 ws-content-area">

                <?php
                get_template_part(
                    'template-parts/archive/archive-loop'
                );
                ?>

            </main>

            <aside class="col-12 col-lg-4 ws-sidebar">

                <?php
                get_template_part(
                    'template-parts/layout/sidebar'
                );
                ?>

            </aside>

        </div>

    </div>

</div>