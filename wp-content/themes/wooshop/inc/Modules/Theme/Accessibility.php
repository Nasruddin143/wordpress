<?php
/**
 * Accessibility Module
 *
 * Provides WooShop accessibility enhancements for navigation,
 * focus states, skip links, images, and assistive technologies.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Accessibility class.
 */
class Accessibility {

    /**
     * Register accessibility hooks.
     *
     * @return void
     */
    public function register() {

        add_action(
            'wp_body_open',
            array( $this, 'render_skip_link' ),
            5
        );

        add_filter(
            'the_content_more_link',
            array( $this, 'filter_more_link' )
        );

        add_filter(
            'get_search_form',
            array( $this, 'filter_search_form' )
        );

        add_filter(
            'nav_menu_link_attributes',
            array( $this, 'filter_menu_link_attributes' ),
            10,
            4
        );

        add_filter(
            'wp_get_attachment_image_attributes',
            array( $this, 'filter_image_attributes' ),
            10,
            3
        );
    }

    /**
     * Render the primary skip link.
     *
     * @return void
     */
    public function render_skip_link() {

        echo '<a class="ws-skip-link visually-hidden-focusable" href="#primary">' .
            esc_html__( 'Skip to content', 'wooshop' ) .
            '</a>';
    }

    /**
     * Improve the more link accessibility label.
     *
     * @param string $link More link HTML.
     * @return string
     */
    public function filter_more_link( $link ) {

        return str_replace(
            '>',
            ' aria-label="' .
            esc_attr__( 'Continue reading', 'wooshop' ) .
            '">',
            $link,
            1
        );
    }

    /**
     * Add an accessible label to the search form.
     *
     * @param string $form Search form HTML.
     * @return string
     */
    public function filter_search_form( $form ) {

        if ( false === strpos( $form, 'aria-label=' ) ) {

            $form = str_replace(
                '<form',
                '<form aria-label="' .
                esc_attr__( 'Site search', 'wooshop' ) .
                '"',
                $form
            );
        }

        return $form;
    }

    /**
     * Add accessibility attributes to navigation links.
     *
     * @param array    $atts  Link attributes.
     * @param WP_Post  $item  Menu item.
     * @param stdClass $args  Menu arguments.
     * @param int      $depth Menu depth.
     * @return array
     */
    public function filter_menu_link_attributes(
        $atts,
        $item,
        $args,
        $depth
    ) {

        if ( ! empty( $item->current ) ) {
            $atts['aria-current'] = 'page';
        }

        return $atts;
    }

    /**
     * Improve attachment image attributes.
     *
     * WordPress normally generates alt attributes from attachment metadata.
     * This ensures the attribute is always present.
     *
     * @param array  $attr       Image attributes.
     * @param WP_Post $attachment Attachment object.
     * @param mixed  $size        Requested image size.
     * @return array
     */
    public function filter_image_attributes(
        $attr,
        $attachment,
        $size
    ) {

        if ( ! isset( $attr['alt'] ) ) {
            $attr['alt'] = '';
        }

        return $attr;
    }
}