<?php
/**
 * Homepage Promotional Section
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="ws-home-section ws-home-promo">

    <div class="ws-container">

        <div class="ws-promo">

            <div class="ws-promo__content">

                <p class="ws-promo__eyebrow">

                    <?php
                    esc_html_e(
                        'Special Offer',
                        'wooshop'
                    );
                    ?>

                </p>

                <h2 class="ws-promo__title">

                    <?php
                    esc_html_e(
                        'Discover something new.',
                        'wooshop'
                    );
                    ?>

                </h2>

                <p class="ws-promo__description">

                    <?php
                    esc_html_e(
                        'Explore our latest products and discover great deals.',
                        'wooshop'
                    );
                    ?>

                </p>

                <a
                    class="ws-button ws-button--primary"
                    href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">

                    <?php
                    esc_html_e(
                        'Explore Shop',
                        'wooshop'
                    );
                    ?>

                </a>

            </div>

        </div>

    </div>

</section>