<?php

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
	return;
}

echo wc_get_stock_html($product);

if ($product->is_in_stock()) :
?>

	<?php do_action('woocommerce_before_add_to_cart_form'); ?>

	<form class="cart"
		action="<?php echo esc_url(
					apply_filters(
						'woocommerce_add_to_cart_form_action',
						$product->get_permalink()
					)
				); ?>"
		method="post"
		enctype="multipart/form-data">

		<?php do_action('woocommerce_before_add_to_cart_button'); ?>

		<div class="d-flex align-items-center gap-3 flex-wrap">

			<!-- Quantity -->
			<div class="kt-quantity d-flex align-items-center">

				<button
					type="button"
					class="btn btn-outline-secondary kt-qty-btn kt-qty-minus">

					−

				</button>

				<?php
				woocommerce_quantity_input(
					array(
						'min_value'   => $product->get_min_purchase_quantity(),
						'max_value'   => $product->get_max_purchase_quantity(),
						'input_value' => $product->get_min_purchase_quantity(),
					)
				);
				?>

				<button
					type="button"
					class="btn btn-outline-secondary kt-qty-btn kt-qty-plus">

					+

				</button>

			</div>

			<!-- Add To Cart -->
			<button
				type="submit"
				name="add-to-cart"
				value="<?php echo esc_attr($product->get_id()); ?>"
				class="single_add_to_cart_button btn btn-primary px-4 py-2">

				<i class="bi bi-cart3 me-2"></i>

				<?php
				echo esc_html(
					$product->single_add_to_cart_text()
				);
				?>

			</button>

		</div>

		<?php do_action('woocommerce_after_add_to_cart_button'); ?>

	</form>

	<?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif;
