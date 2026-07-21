<?php

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------
// ELEMENT: Carousel Slider (Enhanced)
// -----------------------------------

if (defined('WPB_VC_VERSION')) {

	add_action('vc_before_init', 'kt_vc_slider_banner');
	function kt_vc_slider_banner()
	{

		vc_map([
			'name' => __('Slider Banner', 'kt'),
			'base' => 'slider_banner',
			'icon' => 'dashicons-format-gallery',
			'category' => __('Custom Components', 'kt'),
			'description' => __('Advanced slider with autoplay, speed & controls', 'kt'),

			'params' => [

				[
					'type' => 'textfield',
					'heading' => __('Number of Slides', 'kt'),
					'param_name' => 'count',
					'value' => '5',
				],

				[
					'type' => 'dropdown',
					'heading' => __('Autoplay', 'kt'),
					'param_name' => 'autoplay',
					'value' => [
						__('Enable', 'kt') => 'true',
						__('Disable', 'kt') => 'false',
					],
					'std' => 'true',
				],

				[
					'type' => 'textfield',
					'heading' => __('Autoplay Speed (ms)', 'kt'),
					'param_name' => 'speed',
					'value' => '4000',
					'dependency' => [
						'element' => 'autoplay',
						'value' => 'true',
					],
				],

				[
					'type' => 'textfield',
					'heading' => __('Transition Duration (ms)', 'kt'),
					'param_name' => 'transition',
					'value' => '800',
				],

				[
					'type' => 'dropdown',
					'heading' => __('Show Pagination Dots?', 'kt'),
					'param_name' => 'dots',
					'value' => [
						__('Show', 'kt') => 'true',
						__('Hide', 'kt') => 'false',
					],
					'std' => 'true',
				],

				[
					'type' => 'dropdown',
					'heading' => __('Show Prev/Next Buttons?', 'kt'),
					'param_name' => 'arrows',
					'value' => [
						__('Show', 'kt') => 'true',
						__('Hide', 'kt') => 'false',
					],
					'std' => 'true',
				],

				[
					'type' => 'dropdown',
					'heading' => __('Enable Loop?', 'kt'),
					'param_name' => 'loop',
					'value' => [
						__('Enable', 'kt') => 'true',
						__('Disable', 'kt') => 'false',
					],
					'std' => 'true',
				],

				[
					'type' => 'dropdown',
					'heading' => __('Pause on Hover?', 'kt'),
					'param_name' => 'pause',
					'value' => [
						__('Enable', 'kt') => 'true',
						__('Disable', 'kt') => 'false',
					],
					'std' => 'true',
				],
			],
		]);
	}

	// -----------------------------------
	// SHORTCODE CALLBACK (OPTIMIZED)
	// -----------------------------------
	add_shortcode('slider_banner', 'kt_slider_banner_shortcode');
	function kt_slider_banner_shortcode($atts)
	{
		$atts = shortcode_atts([
			'count' => 5,
			'autoplay' => 'true',
			'speed' => 4000,
			'transition' => 800,
			'dots' => 'true',
			'arrows' => 'true',
			'loop' => 'true',
			'pause' => 'true',
		], $atts, 'slider_banner');

		/**
		 * Cache key
		 */
		$cache_key = 'slider_' . md5(serialize($atts));


		/**
		 * Try load from KT_Cache
		 */
		$cached = KT_Cache::get($cache_key, 'slider');

		if ($cached !== false) {
			return $cached;
		}

		$slides = new WP_Query([
			'post_type' => 'slider',
			'posts_per_page' => (int) $atts['count'],
			'post_status' => 'publish',

			'no_found_rows' => true,
			'ignore_sticky_posts' => true,

			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,

			'cache_results' => true,
		]);

		ob_start();

		if ($slides->have_posts()):

			$slider_id = 'kt_slider_banner_' . wp_rand(1000, 9999);
?>

			<section id="<?php echo esc_attr($slider_id); ?>" class="carousel slide" data-carousel-options='<?php echo esc_attr(wp_json_encode([
																												'interval' => (int) $atts['speed'],
																												'ride' => ($atts['autoplay'] === 'true') ? 'carousel' : false,
																												'wrap' => ($atts['loop'] === 'true'),
																												'pause' => ($atts['pause'] === 'true') ? 'hover' : false,
																											])); ?>'>

				<div class="carousel-inner">

					<?php
					$i = 0;
					while ($slides->have_posts()):
						$slides->the_post();

						$image_id = get_post_thumbnail_id();
						$title = get_the_title();
						$excerpt = wp_trim_words(get_the_excerpt(), 20);
						$slide_url = function_exists('get_field')
							? get_field('slide_url')
							: get_post_meta(get_the_ID(), 'slide_url', true);

					?>

						<div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>">

							<?php
							if ($image_id) {
								$image = wp_get_attachment_image(
									$image_id,
									'kt-hero',
									false,
									[
										'class' => 'img-fluid',
										'alt' => esc_attr($title),
										'title' => esc_attr($title),
										'loading' => ($i === 0) ? 'eager' : 'lazy',
										'decoding' => 'async',
										'fetchpriority' => ($i === 0) ? 'high' : 'auto',
									]
								);

								if (!empty($slide_url)) {
									echo '<a href="' . esc_url($slide_url) . '" class="kt-slide-link" aria-label="' . esc_attr($title) . '">';
									echo $image;
									echo '</a>';
								} else {
									echo $image;
								}
							}
							?>

							<!-- <div class="w-50 h-100 mini-blog float-left position-absolute top-0 start-0 d-flex align-items-center"> -->
							<div class="w-100 h-100 float-left position-absolute top-0 start-0 d-flex align-items-center">
								<div class="container">
									<div class="row">
										<div class="col-lg-6 col-md-8 col-sm-12 d-sm-none d-md-block d-grid row-gap-3">

											<h2 class="h1 fw-bold text-white"><?php echo esc_html($title); ?></h2>
											<p class="lead text-white"><?php echo esc_html($excerpt); ?></p>

											<?php if (!empty($slide_url)): ?>

												<div>
													<a href="<?php echo esc_url($slide_url); ?>" class="btn btn-lg btn-outline-light rounded-pill px-4 py-2" role="button" title="<?php echo esc_html('Shop Products', 'kt'); ?>">
														<?php echo esc_html('Shop Products', 'kt'); ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php
						$i++;
					endwhile;
					?>

				</div>

				<?php if ($atts['arrows'] === 'true'): ?>
					<button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>"
						data-bs-slide="prev">
						<span class="carousel-control-prev-icon"></span>
						<span class="visually-hidden">
							<?php esc_html_e('Previous', 'kt'); ?>
						</span>
					</button>

					<button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>"
						data-bs-slide="next">
						<span class="carousel-control-next-icon"></span>
						<span class="visually-hidden">
							<?php esc_html_e('Next', 'kt'); ?>
						</span>
					</button>
				<?php endif; ?>

				<?php if ($atts['dots'] === 'true'): ?>
					<div class="carousel-indicators">
						<?php for ($n = 0; $n < $i; $n++): ?>
							<button type="button" aria-label="Go to slide <?php echo esc_attr($n + 1); ?>"
								data-bs-target="#<?php echo esc_attr($slider_id); ?>" data-bs-slide-to="<?php echo esc_attr($n); ?>"
								class="<?php echo ($n === 0) ? 'active' : ''; ?>">
							</button>
						<?php endfor; ?>
					</div>
				<?php endif; ?>

			</section>

<?php endif;

		wp_reset_postdata();
		$output = ob_get_clean();

		/**
		 * Save to KT_Cache
		 */
		KT_Cache::set(
			$cache_key,
			$output,
			'slider',
			(6 * HOUR_IN_SECONDS)
		);

		return $output;
	}
} else {

	// Admin notice if WPBakery is not active
	add_action('admin_notices', function () {

		echo '<div class="notice notice-warning is-dismissible">
		<p>
			<strong>' . esc_html__('WPBakery Page Builder is not installed or activated.', 'kt') . '</strong>
			' . esc_html__('The Slider Banner element will not work until WPBakery is active.', 'kt') . '
		</p>
	</div>';
	});
}


add_action('save_post_slider', function () {

	KT_Cache::flush_group('slider');
});

add_action('deleted_post', function ($post_id) {

	if (get_post_type($post_id) === 'slider') {

		KT_Cache::flush_group('slider');
	}
});
