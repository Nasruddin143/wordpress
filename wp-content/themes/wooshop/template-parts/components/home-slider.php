<?php
/**
 * WooShop Slider
 *
 * Bootstrap 5 carousel component.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

$slides = get_posts(
    array(
        'post_type'      => 'slider',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    )
);

if ( empty( $slides ) ) {
    return;
}
?>

<section class="wooshop-slider" aria-label="<?php esc_attr_e( 'Featured content', 'wooshop' ); ?>">
    <div
        id="wooshop-slider"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="5000"
    >
        <?php if ( count( $slides ) > 1 ) : ?>
            <div class="carousel-indicators">
                <?php foreach ( $slides as $index => $slide ) : ?>
                    <button
                        type="button"
                        data-bs-target="#wooshop-slider"
                        data-bs-slide-to="<?php echo esc_attr( $index ); ?>"
                        class="<?php echo 0 === $index ? 'active' : ''; ?>"
                        aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                        aria-label="<?php printf( esc_attr__( 'Slide %d', 'wooshop' ), $index + 1 ); ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner">
            <?php foreach ( $slides as $index => $slide ) : ?>
                <?php
                $image = get_the_post_thumbnail_url( $slide->ID, 'full' );
                ?>

                <div class="carousel-item <?php echo 0 === $index ? 'active' : ''; ?>">
                    <?php if ( $image ) : ?>
                        <img
                            src="<?php echo esc_url( $image ); ?>"
                            class="d-block w-100"
                            alt="<?php echo esc_attr( get_the_title( $slide->ID ) ); ?>"
                        >
                    <?php endif; ?>

                    <div class="carousel-caption d-none d-md-block">
                        <h2>
                            <?php echo esc_html( get_the_title( $slide->ID ) ); ?>
                        </h2>

                        <?php
                        $excerpt = get_the_excerpt( $slide->ID );

                        if ( $excerpt ) :
                            ?>
                            <p>
                                <?php echo esc_html( $excerpt ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( count( $slides ) > 1 ) : ?>
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#wooshop-slider"
                data-bs-slide="prev"
                aria-label="<?php esc_attr_e( 'Previous slide', 'wooshop' ); ?>"
            >
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>

            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#wooshop-slider"
                data-bs-slide="next"
                aria-label="<?php esc_attr_e( 'Next slide', 'wooshop' ); ?>"
            >
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        <?php endif; ?>
    </div>
</section>