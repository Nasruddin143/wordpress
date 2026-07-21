<?php

defined('ABSPATH') || exit;

get_header('shop');
?>

<?php get_template_part('template-parts/content', 'custom-header'); ?>

<main id="primary" class="site-main woocommerce-shop py-5">

	<div class="container">

		<div class="kt-breadcrumb mb-4">
			<?php woocommerce_breadcrumb(); ?>
		</div>

		<?php if (woocommerce_product_loop()): ?>

			<div class="row g-4">

				<!-- Sidebar -->
				<?php if (is_active_sidebar('shop-sidebar')): ?>

					<aside class="col-lg-3">

						<div class="sticky-top" style="top:100px;">

							<?php dynamic_sidebar('shop-sidebar'); ?>

						</div>

					</aside>

				<?php endif; ?>

				<!-- Products -->
				<div class="<?php echo is_active_sidebar('shop-sidebar') ? 'col-lg-9' : 'col-12'; ?>">

					<header class="woocommerce-shop-header mb-4">

						<h1 class="woocommerce-products-header__title page-title h2 fw-bold mb-2">
							<?php woocommerce_page_title(); ?>
						</h1>

						<?php woocommerce_output_all_notices(); ?>

					</header>

					<div class="kt-options">
						<!-- Shop Toolbar -->
						<div class="row align-items-center g-3">

							<!-- Result Count -->
							<div class="col-lg-6">

								<div class="fs-medium text-muted">

									<?php woocommerce_result_count(); ?>

								</div>

							</div>

							<!-- Sorting -->
							<div class="col-lg-6">

								<div class="d-flex justify-content-lg-end">

									<?php woocommerce_catalog_ordering(); ?>

								</div>

							</div>

						</div>
					</div>

					<div id="ktProducts" class="row g-4 kt-products-grid">

						<?php
						if (wc_get_loop_prop('total')) {

							while (have_posts()) {

								the_post();

								do_action('woocommerce_shop_loop');

								wc_get_template_part('content', 'product');
							}
						}
						?>

					</div>

					<!-- Pagination -->
					<div class="mt-5">

						<?php do_action('woocommerce_after_shop_loop'); ?>

					</div>

				</div>

			</div>

		<?php else: ?>

			<?php do_action('woocommerce_no_products_found'); ?>

		<?php endif; ?>

	</div>

	<?php do_action('woocommerce_after_main_content'); ?>

</main>

<?php get_footer('shop');
