<?php
/**
 * Media Helpers
 *
 * Theme-level media helpers.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render an attachment image.
 *
 * @param int $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param array $attr Image attributes.
 *
 * @return string
 */
function wooshop_image(
    int $attachment_id,
    string $size = 'wooshop-card',
    array $attr = []
): string {

    if ( ! $attachment_id ) {
        return '';
    }

    return wp_get_attachment_image(
        $attachment_id,
        $size,
        false,
        $attr
    );
}