<?php
defined('ABSPATH') || exit;

$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (empty($product_tabs)) {
	return;
}
?>

<div class="card border-0 shadow">
	<div class="card-body">
		<div class="woocommerce-tabs wc-tabs-wrapper kt-product-tabs">

			<!-- Nav Tabs -->
			<ul class="nav nav-tabs" id="productTabs" role="tablist">

				<?php
				$counter = 0;

				foreach ($product_tabs as $key => $product_tab):

					$active = ($counter === 0) ? 'active' : '';
				?>

					<li class="nav-item me-4" role="presentation">

						<button
							class="nav-link <?php echo esc_attr($active); ?> py-3 px-0"
							id="tab-<?php echo esc_attr($key); ?>-tab"
							data-bs-toggle="tab"
							data-bs-target="#tab-<?php echo esc_attr($key); ?>"
							type="button"
							role="tab"
							aria-controls="tab-<?php echo esc_attr($key); ?>"
							aria-selected="<?php echo ($counter === 0) ? 'true' : 'false'; ?>">

							<?php
							echo wp_kses_post(
								apply_filters(
									'woocommerce_product_' . $key . '_tab_title',
									$product_tab['title'],
									$key
								)
							);
							?>

						</button>

					</li>

				<?php
					$counter++;
				endforeach;
				?>

			</ul>

			<!-- Tab Content -->
			<div class="tab-content py-4 bg-transparent" id="productTabsContent">

				<?php
				$counter = 0;

				foreach ($product_tabs as $key => $product_tab):

					$active = ($counter === 0)
						? 'show active'
						: '';
				?>

					<div
						class="tab-pane fade <?php echo esc_attr($active); ?>"
						id="tab-<?php echo esc_attr($key); ?>"
						role="tabpanel"
						aria-labelledby="tab-<?php echo esc_attr($key); ?>-tab">

						<div class="border-top-0 rounded-top-0 border-0">


							<?php
							if (isset($product_tab['callback'])) {
								call_user_func(
									$product_tab['callback'],
									$key,
									$product_tab
								);
							}
							?>


						</div>

					</div>

				<?php
					$counter++;
				endforeach;
				?>

			</div>

			<?php do_action('woocommerce_product_after_tabs'); ?>

		</div>
	</div>
</div>