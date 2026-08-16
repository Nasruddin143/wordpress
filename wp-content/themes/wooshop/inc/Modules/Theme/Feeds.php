<?php
/**
 * WordPress Feeds.
 *
 * Handles feed-related functionality for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress feed functionality.
 */
class Feeds extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'feed_links_show_comments_feed',
            [ $this, 'disable_comments_feed' ]
        );
    }

    /**
     * Disable the separate comments feed link.
     *
     * The main post feed remains enabled.
     *
     * @param bool $show Whether the comments feed link should be shown.
     * @return bool
     */
    public function disable_comments_feed( bool $show ): bool {

        return false;
    }
}