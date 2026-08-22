<?php
/**
 * Comments Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Comments module.
 */
final class Comments extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        add_filter(
            'comment_form_default_fields',
            array( $this, 'comment_fields' )
        );
    }

    /**
     * Modify comment fields.
     *
     * @param array<string, string> $fields Comment fields.
     *
     * @return array<string, string>
     */
    public function comment_fields( array $fields ): array {

        if ( isset( $fields['url'] ) ) {
            $fields['url'] = sprintf(
                '<p class="comment-form-url">
					<label for="url">%s</label>
					<input id="url" name="url" type="url" value="" size="30" />
				</p>',
                esc_html__( 'Website', 'wooshop' )
            );
        }

        return $fields;
    }
}