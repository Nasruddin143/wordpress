<?php
/**
 * WooCommerce Variation Swatch Metadata
 *
 * Handles variation swatch term metadata.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class VariationSwatchMeta extends Module
{

    /**
     * Swatch type metadata key.
     *
     * @var string
     */
    private const TYPE_KEY = 'wooshop_swatch_type';

    /**
     * Swatch color metadata key.
     *
     * @var string
     */
    private const COLOR_KEY = 'wooshop_swatch_color';

    /**
     * Swatch image metadata key.
     *
     * @var string
     */
    private const IMAGE_KEY = 'wooshop_swatch_image';


    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        add_action(
            'created_term',
            [ $this, 'created_term' ],
            10,
            3
        );

        add_action(
            'edited_term',
            [ $this, 'edited_term' ],
            10,
            3
        );
    }


    /**
     * Handle newly created attribute term.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy name.
     *
     * @return void
     */
    public function created_term(
        int $term_id,
        int $tt_id,
        string $taxonomy
    ): void {

        if ( ! $this->is_attribute_taxonomy( $taxonomy ) ) {
            return;
        }

        $this->ensure_defaults( $term_id );
    }


    /**
     * Handle edited attribute term.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy name.
     *
     * @return void
     */
    public function edited_term(
        int $term_id,
        int $tt_id,
        string $taxonomy
    ): void {

        if ( ! $this->is_attribute_taxonomy( $taxonomy ) ) {
            return;
        }

        $this->ensure_defaults( $term_id );
    }


    /**
     * Determine whether taxonomy is a WooCommerce
     * global attribute taxonomy.
     *
     * @param string $taxonomy Taxonomy name.
     *
     * @return bool
     */
    private function is_attribute_taxonomy(
        string $taxonomy
    ): bool {

        return 0 === strpos(
                $taxonomy,
                'pa_'
            );
    }


    /**
     * Ensure default swatch metadata exists.
     *
     * @param int $term_id Term ID.
     *
     * @return void
     */
    private function ensure_defaults(
        int $term_id
    ): void {

        $type = get_term_meta(
            $term_id,
            self::TYPE_KEY,
            true
        );

        if ( '' === $type ) {

            update_term_meta(
                $term_id,
                self::TYPE_KEY,
                'label'
            );
        }
    }


    /**
     * Get swatch type.
     *
     * @param int $term_id Term ID.
     *
     * @return string
     */
    public function get_type(
        int $term_id
    ): string {

        $type = get_term_meta(
            $term_id,
            self::TYPE_KEY,
            true
        );

        if ( ! in_array(
            $type,
            [
                'label',
                'color',
                'image',
            ],
            true
        ) ) {
            return 'label';
        }

        return $type;
    }


    /**
     * Get swatch color.
     *
     * @param int $term_id Term ID.
     *
     * @return string
     */
    public function get_color(
        int $term_id
    ): string {

        $color = get_term_meta(
            $term_id,
            self::COLOR_KEY,
            true
        );

        if ( ! is_string( $color ) ) {
            return '';
        }

        $color = sanitize_hex_color( $color );

        return $color ?: '';
    }


    /**
     * Get swatch image attachment ID.
     *
     * @param int $term_id Term ID.
     *
     * @return int
     */
    public function get_image(
        int $term_id
    ): int {

        return absint(
            get_term_meta(
                $term_id,
                self::IMAGE_KEY,
                true
            )
        );
    }


    /**
     * Get complete swatch metadata.
     *
     * @param int $term_id Term ID.
     *
     * @return array
     */
    public function get_data(
        int $term_id
    ): array {

        return [
            'type'  => $this->get_type( $term_id ),
            'color' => $this->get_color( $term_id ),
            'image' => $this->get_image( $term_id ),
        ];
    }


    /**
     * Save swatch type.
     *
     * This method will be used by the admin UI
     * in a later milestone.
     *
     * @param int    $term_id Term ID.
     * @param string $type    Swatch type.
     *
     * @return bool
     */
    public function save_type(
        int $term_id,
        string $type
    ): bool {

        if ( ! in_array(
            $type,
            [
                'label',
                'color',
                'image',
            ],
            true
        ) ) {
            $type = 'label';
        }

        return (bool) update_term_meta(
            $term_id,
            self::TYPE_KEY,
            $type
        );
    }


    /**
     * Save swatch color.
     *
     * @param int    $term_id Term ID.
     * @param string $color   Color value.
     *
     * @return bool
     */
    public function save_color(
        int $term_id,
        string $color
    ): bool {

        $color = sanitize_hex_color(
            $color
        );

        if ( ! $color ) {

            delete_term_meta(
                $term_id,
                self::COLOR_KEY
            );

            return true;
        }

        return (bool) update_term_meta(
            $term_id,
            self::COLOR_KEY,
            $color
        );
    }


    /**
     * Save swatch image attachment ID.
     *
     * @param int $term_id     Term ID.
     * @param int $attachment_id Attachment ID.
     *
     * @return bool
     */
    public function save_image(
        int $term_id,
        int $attachment_id
    ): bool {

        $attachment_id = absint(
            $attachment_id
        );

        if ( ! $attachment_id ) {

            delete_term_meta(
                $term_id,
                self::IMAGE_KEY
            );

            return true;
        }

        return (bool) update_term_meta(
            $term_id,
            self::IMAGE_KEY,
            $attachment_id
        );
    }
}