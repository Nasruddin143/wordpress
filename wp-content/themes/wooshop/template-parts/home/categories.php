<?php
/**
 * Homepage Product Categories
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

$categories = get_terms(
    [
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 8,
    ]
);

if ( empty( $categories ) || is_wp_error( $categories ) ) {
    return;
}
?>

<section
    class="ws-home-section ws-home-categories"
    aria-labelledby="ws-home-categories-title">

    <div class="ws-container">

        <header class="ws-section-header">

            <h2
                id="ws-home-categories-title"
                class="ws-section-title">

                <?php
                esc_html_e(
                    'Shop by Category',
                    'wooshop'
                );
                ?>

            </h2>

        </header>

        <div class="ws-category-grid">

            <?php foreach ( $categories as $category ) : ?>

                <?php
                $thumbnail_id = get_term_meta(
                    $category->term_id,
                    'thumbnail_id',
                    true
                );

                $category_url = get_term_link( $category );

                if ( is_wp_error( $category_url ) ) {
                    continue;
                }
                ?>

                <article class="ws-category-card">

                    <a
                        class="ws-category-card__link"
                        href="<?php echo esc_url( $category_url ); ?>">

                        <?php if ( $thumbnail_id ) : ?>

                            <div class="ws-category-card__image">

                                <?php
                                echo wp_get_attachment_image(
                                    $thumbnail_id,
                                    'woocommerce_thumbnail',
                                    false,
                                    [
                                        'loading' => 'lazy',
                                    ]
                                );
                                ?>

                            </div>

                        <?php endif; ?>

                        <h3 class="ws-category-card__title">

                            <?php
                            echo esc_html(
                                $category->name
                            );
                            ?>

                        </h3>

                    </a>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>