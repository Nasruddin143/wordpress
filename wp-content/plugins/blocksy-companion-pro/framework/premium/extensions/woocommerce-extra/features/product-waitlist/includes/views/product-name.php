<?php

if (! defined('ABSPATH')) {
	exit;
}

$product_name = blocksy_companion_get_ext('woocommerce-extra')
	->utils
	->get_formatted_title($product->get_id());

if ($product->is_type('variation')) {
	$parent_product = wc_get_product($product->get_parent_id());
	if ($parent_product) {
		$product_name = blocksy_companion_get_ext(
			'woocommerce-extra'
		)->utils->get_formatted_title($product->get_id());
	}
}

$product_permalink = $product->is_visible() ? $product->get_permalink() : '';

echo wp_kses_post(blocksy_companion_safe_sprintf(
	'<a href="%s" class="product-name">%s</a>',
	esc_url($product_permalink),
	$product_name
));

if ($product->is_type('variation')) {
	$maybeVariationsAttrs = $product->get_attributes();

	$withDefaultVariation = 'no';

	if ($product->is_type('variable')) {
		$maybeDefaultVariation = null;

		if (blocksy_companion_theme_functions()->blocksy_manager()) {
			$maybeDefaultVariation = blocksy_companion_theme_functions()->blocksy_manager()
				->woocommerce
				->retrieve_product_default_variation($product);
		}

		if ($maybeDefaultVariation) {
			$withDefaultVariation = 'yes';

			$maybeVariationsAttrs = $maybeDefaultVariation->get_attributes();
		}
	}

	if (
		isset($maybeVariations['attributes'])
		&&
		! empty($maybeVariations['attributes'])
	) {
		$maybeVariationsAttrs = array_merge(
			$maybeVariationsAttrs,
			$maybeVariations['attributes']
		);
	}

	$attributes_html = [];
	$visible_attributes_count = 0;

	foreach ($maybeVariationsAttrs as $key => $value) {
		$attribute_slug = str_replace('attribute_', '', sanitize_title($key));
		$attribute_label = $attribute_slug;

		if (taxonomy_is_product_attribute($attribute_slug)) {
			$labels = wc_get_attribute_taxonomy_labels();
			$taxonomy_slug = wc_attribute_taxonomy_slug($attribute_slug);

			if (isset($labels[$taxonomy_slug])) {
				$attribute_label = $labels[$taxonomy_slug];
			}
		} else {
			$attributes = [];

			if ($parent_product) {
				$attributes = $parent_product->get_attributes();
			}

			if (
				isset($attributes[$attribute_slug])
				&&
				is_object($attributes[$attribute_slug])
				&&
				method_exists($attributes[$attribute_slug], 'get_name')
			) {
				$attribute_label = $attributes[$attribute_slug]->get_name();
			}
		}

		$term = get_term_by( 'slug', $value, $attribute_slug);

		$attribute_name = $value;
		$attribute_value = $value;

		if ( $term && ! is_wp_error( $term )  ) {
			$attribute_name = $term->name;
			$attribute_value = $term->slug;
		}

		if (
			$value
		) {
			$visible_attributes_count++;

			$attributes_html[] = blocksy_companion_html_tag(
				'dt',
				[
					'data-attribute-slug' => $attribute_slug,
					'data-attribute-val' => $attribute_value
				],
				$attribute_label . ':'
			);

			$attributes_html[] = blocksy_companion_html_tag(
				'dd',
				[],
				$attribute_name
			);
		}
	}

	if ($visible_attributes_count > 2) {
		blocksy_companion_html_tag_e(
			'dl',
			[
				'class' => 'variation',
				'data-default' => $withDefaultVariation
			],
			implode('', $attributes_html)
		);
	}
}

// Output the price directly via get_price_html() instead of
// woocommerce_template_single_price() so a child-theme override of
// single-product/price.php (meant for the main product summary) doesn't leak
// its markup into this compact waitlist row.
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<p class="price">' . $product->get_price_html() . '</p>';
