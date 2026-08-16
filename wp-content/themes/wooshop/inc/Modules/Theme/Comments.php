<?php
/**
 * WordPress Comments.
 *
 * Handles comment-related theme functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress comment functionality.
 */
class Comments extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'comment_form_defaults',
            [ $this, 'comment_form_defaults' ]
        );

        add_filter(
            'comment_form_fields',
            [ $this, 'reorder_comment_fields' ]
        );
    }

    /**
     * Customize the default comment form.
     *
     * @param array $defaults Comment form defaults.
     * @return array
     */
    public function comment_form_defaults( array $defaults ): array {

        $defaults['class_form'] = 'comment-form';

        $defaults['class_submit'] = 'btn btn-primary';

        $defaults['label_submit'] = esc_html__(
            'Post Comment',
            'wooshop'
        );

        return $defaults;
    }

    /**
     * Reorder comment fields.
     *
     * @param array $fields Comment fields.
     * @return array
     */
    public function reorder_comment_fields( array $fields ): array {

        $ordered_fields = [];

        if ( isset( $fields['author'] ) ) {
            $ordered_fields['author'] = $fields['author'];
        }

        if ( isset( $fields['email'] ) ) {
            $ordered_fields['email'] = $fields['email'];
        }

        if ( isset( $fields['url'] ) ) {
            $ordered_fields['url'] = $fields['url'];
        }

        if ( isset( $fields['cookies'] ) ) {
            $ordered_fields['cookies'] = $fields['cookies'];
        }

        return $ordered_fields;
    }
}