<?php
/**
 * Theme Feeds Module.
 *
 * Handles feed-related theme functionality.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme feeds module.
 */
final class Feeds extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {
        add_action(
            'wp_head',
            array( $this, 'add_feed_links' ),
            5
        );
    }

    /**
     * Add feed discovery links.
     *
     * WordPress already provides feed functionality, so this module
     * only ensures the theme exposes the expected feed discovery links.
     *
     * @return void
     */
    public function add_feed_links(): void {

        if ( ! is_singular() && ! is_home() && ! is_archive() ) {
            return;
        }

        $feed_url = get_bloginfo( 'rss2_url' );

        if ( '' === $feed_url ) {
            return;
        }
        ?>
        <link
            rel="alternate"
            type="application/rss+xml"
            title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
            href="<?php echo esc_url( $feed_url ); ?>"
        />
        <?php
    }
}