<?php
$slider_posts = get_posts([
    'post_type' => 'slider',
    'numberposts' => 5,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
]);
?>

<?php if (!empty($slider_posts)): ?>
    <div id="ktSlider" class="carousel slide" data-bs-ride="carousel">

        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php foreach ($slider_posts as $index => $post): ?>
                <button type="button" data-bs-target="#ktSlider" data-bs-slide-to="<?php echo $index; ?>"
                    class="<?php echo $index === 0 ? 'active' : ''; ?>"
                    aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($slider_posts as $index => $post):
                setup_postdata($post); ?>

                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('kt-hero', ['class' => 'd-block w-100 rounded-3']); ?>
                    <?php endif; ?>

                    <div class="w-100 h-100 float-left position-absolute top-0 start-0 d-flex align-items-center">
                        <div class="container px-5">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-sm-12 d-grid row-gap-3">

                                    <h2 class="fw-bold text-blue mb-0"><?php the_title(); ?></h2>
                                    <p class="text-dark fw-medium"><?php echo wp_trim_words(get_the_content(), 15); ?></p>

                                    <div>
                                        <a href="https://kingtailors.co.in/services/" class="btn btn-dark px-3 py-2" role="button" title="Shop Products">
                                            Shop Now <i data-feather="arrow-right" class="feather-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach;
            wp_reset_postdata(); ?>
        </div>

        <!-- Controls -->
        <!-- <button class="carousel-control-prev" type="button" data-bs-target="#ktSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#ktSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button> -->

    </div>
<?php endif;
