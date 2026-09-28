<?php

namespace Blocksy;

if (! defined('ABSPATH')) {
	exit;
}

class VideoImportExport {
	private $blocksy_column_id = 'blocksy_images_metadata';

	public function __construct() {
		add_filter(
			"woocommerce_product_export_product_default_columns",
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_product_export_column_names',
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_csv_product_import_mapping_options',
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_csv_product_import_mapping_default_columns',
			[$this, 'default_import_column_name']
		);

		add_filter(
			"woocommerce_product_export_product_column_{$this->blocksy_column_id}",
			[$this, 'export_custom_data'],
			10, 2
		);

		add_filter(
			'woocommerce_product_import_inserted_product_object',
			[$this, 'attach_images_metadata'],
			10, 2
		);
	}

	public function attach_images_metadata($product, $data) {
		if (empty($data[$this->blocksy_column_id])) {
			return $product;
		}

		$images = \Blocksy\WooImportExport::parse_data(
			$data[$this->blocksy_column_id]
		);

		if (empty($images[0])) {
			return $product;
		}

		$attachments = [];

		foreach ($this->get_images_ids($product) as $image_id) {
			$attachments[wp_get_attachment_url($image_id)] = $image_id;

			$source = get_post_meta($image_id, '_wc_attachment_source', true);

			if ($source) {
				$attachments[$source] = $image_id;
			}
		}

		foreach ($images[0] as $image) {
			if (empty($image['url']) || ! isset($attachments[$image['url']])) {
				continue;
			}

			$metadata = blocksy_companion_theme_functions()->blocksy_sanitize_post_meta_options(
				$image['meta']
			);

			if (! is_array($metadata)) {
				continue;
			}

			$has_video_upload = isset($metadata['media_video_source'])
				&& $metadata['media_video_source'] === 'upload'
				&& ! empty($metadata['media_video_upload']);

			if ($has_video_upload) {
				$video_upload = \Blocksy\WooImportExport::upload_video_from_url(
					$metadata['media_video_upload']
				);

				if (! is_wp_error($video_upload)) {
					$metadata['media_video_upload'] = wp_get_attachment_url($video_upload);
				}
			}

			update_post_meta(
				$attachments[$image['url']],
				'blocksy_post_meta_options',
				$metadata
			);
		}

		return $product;
	}

	public function default_import_column_name($columns) {
		$columns[__('Blocksy Images Metadata', 'blocksy-companion')] = $this->blocksy_column_id;

		return $columns;
	}

	public function export_column_name($columns) {
		$columns[$this->blocksy_column_id] = __('Blocksy Images Metadata', 'blocksy-companion');

		return $columns;
	}

	public function export_custom_data($value, $product) {
		$data = [];

		foreach ($this->get_images_ids($product) as $image_id) {
			$image = wp_get_attachment_image_src($image_id, 'full');
			$image_meta = get_post_meta($image_id, 'blocksy_post_meta_options', true);

			if (
				! $image
				||
				empty($image_meta)
			) {
				continue;
			}

			$data[] = [
				'url' => $image[0],
				'attachment_id' => $image_id,
				'meta' => $image_meta,
			];
		}

		if (empty($data)) {
			return '';
		}

		return \Blocksy\WooImportExport::implode_values([json_encode($data)]);
	}

	private function get_images_ids($product) {
		return array_values(array_filter(array_unique(array_merge(
			[$product->get_image_id('edit')],
			$product->get_gallery_image_ids('edit')
		))));
	}
}
