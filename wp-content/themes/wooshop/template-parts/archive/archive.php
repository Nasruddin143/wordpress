<?php
/**
 * Standard Archive Layout
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<main
    id="primary"
    class="site-main">

    <div class="ws-container">

        <?php
        get_template_part(
            'template-parts/archive/archive-header'
        );
        ?>

        <div class="ws-archive-loop">

            <?php
            wooshop_loop();
            ?>

        </div>

        <?php
        get_template_part(
            'template-parts/navigation/pagination'
        );
        ?>

    </div>

</main>