<?php
/**
 * Homepage Hero
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section
    class="ws-home-hero"
    aria-labelledby="ws-home-hero-title">

    <div class="ws-container">

        <div class="ws-home-hero__content">

            <p class="ws-home-hero__eyebrow">
                <?php
                esc_html_e(
                    'Welcome to WooShop',
                    'wooshop'
                );
                ?>
            </p>

            <h1
                id="ws-home-hero-title"
                class="ws-home-hero__title">

                <?php
                esc_html_e(
                    'Everything you need, all in one place.',
                    'wooshop'
                );
                ?>

            </h1>

            <p class="ws-home-hero__description">

                <?php
                esc_html_e(
                    'Discover our latest products, featured collections, and special offers.',
                    'wooshop'
                );
                ?>

            </p>

            <div class="ws-home-hero__actions">

                <a
                    class="ws-button ws-button--primary"
                    href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">

                    <?php
                    esc_html_e(
                        'Shop Now',
                        'wooshop'
                    );
                    ?>

                </a>

            </div>

        </div>

    </div>

</section>