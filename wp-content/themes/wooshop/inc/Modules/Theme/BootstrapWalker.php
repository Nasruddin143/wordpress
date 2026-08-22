<?php
/**
 * WooShop Bootstrap 5 Navigation Walker.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use stdClass;
use Walker_Nav_Menu;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Bootstrap 5 navigation walker.
 */
class BootstrapWalker extends Walker_Nav_Menu {

    /**
     * Current menu item.
     *
     * @var WP_Post|null
     */
    private ?WP_Post $current_item = null;

    /**
     * Bootstrap dropdown alignment classes.
     *
     * @var array<int, string>
     */
    private array $dropdown_menu_alignment_values = array(
        'dropdown-menu-start',
        'dropdown-menu-end',
        'dropdown-menu-sm-start',
        'dropdown-menu-sm-end',
        'dropdown-menu-md-start',
        'dropdown-menu-md-end',
        'dropdown-menu-lg-start',
        'dropdown-menu-lg-end',
        'dropdown-menu-xl-start',
        'dropdown-menu-xl-end',
        'dropdown-menu-xxl-start',
        'dropdown-menu-xxl-end',
    );

    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content.
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args  An object of wp_nav_menu() arguments.
     * @return void
     */
    public function start_lvl( &$output, $depth = 0, $args = null ): void
    {
        $indent = str_repeat( "\t", $depth );

        $dropdown_menu_classes = array();

        if ( $this->current_item instanceof WP_Post ) {
            foreach ( (array) $this->current_item->classes as $class ) {
                if ( in_array( $class, $this->dropdown_menu_alignment_values, true ) ) {
                    $dropdown_menu_classes[] = $class;
                }
            }
        }

        $classes = array(
            'dropdown-menu',
        );

        /**
         * Allow WooShop to modify Bootstrap dropdown menu classes.
         *
         * @param array<int, string> $classes            Dropdown menu classes.
         * @param int                $depth              Current menu depth.
         * @param WP_Post|null      $current_item       Current menu item.
         * @param stdClass|null     $args               Menu arguments.
         */
        $classes = apply_filters(
            'wooshop_bootstrap_nav_menu_dropdown_classes',
            array_merge( $classes, $dropdown_menu_classes ),
            $depth,
            $this->current_item,
            $args
        );

        $output .= "\n$indent<ul class=\"" . esc_attr( implode( ' ', array_filter( $classes ) ) ) . "\">\n";
    }

    /**
     * Starts the element output.
     *
     * @param string    $output Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int       $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int       $id     Current item ID.
     * @return void
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void
    {
        $this->current_item = $item;

        $indent = $depth ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes )
            ? array()
            : (array) $item->classes;

        $has_children = ! empty( $args->walker->has_children );

        /*
         * Bootstrap classes.
         */
        $classes[] = 'nav-item';

        if ( $has_children ) {
            $classes[] = 'dropdown';
        }

        $classes[] = 'nav-item-' . $item->ID;

        /*
         * Remove empty and duplicate classes.
         */
        $classes = array_unique(
            array_filter( $classes )
        );

        /**
         * Filter WooShop Bootstrap menu item classes.
         *
         * @param array<int, string> $classes Menu item classes.
         * @param WP_Post           $item    Menu item.
         * @param stdClass          $args    Menu arguments.
         * @param int                $depth   Menu depth.
         */
        $classes = apply_filters(
            'wooshop_bootstrap_nav_menu_item_classes',
            $classes,
            $item,
            $args,
            $depth
        );

        $class_names = ' class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"';

        $item_id = apply_filters(
            'nav_menu_item_id',
            'menu-item-' . $item->ID,
            $item,
            $args
        );

        $item_id = $item_id
            ? ' id="' . esc_attr( $item_id ) . '"'
            : '';

        $output .= $indent . '<li' . $item_id . $class_names . '>';

        /*
         * Link attributes.
         */
        $attributes = '';

        if ( ! empty( $item->attr_title ) ) {
            $attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';
        }

        if ( ! empty( $item->target ) ) {
            $attributes .= ' target="' . esc_attr( $item->target ) . '"';
        }

        if ( ! empty( $item->xfn ) ) {
            $attributes .= ' rel="' . esc_attr( $item->xfn ) . '"';
        }

        if ( ! empty( $item->url ) ) {
            $attributes .= ' href="' . esc_url( $item->url ) . '"';
        }

        /*
         * Active menu item.
         */
        $is_active = (
            $item->current
            || $item->current_item_ancestor
            || in_array( 'current_page_parent', $item->classes, true )
            || in_array( 'current-post-ancestor', $item->classes, true )
        );

        $active_class = $is_active ? ' active' : '';

        /*
         * Bootstrap link class.
         */
        $link_class = $depth > 0
            ? 'dropdown-item'
            : 'nav-link';

        if ( $has_children ) {
            $link_class .= ' dropdown-toggle';
        }

        $link_attributes = ' class="' . esc_attr( $link_class . $active_class ) . '"';

        /*
         * Bootstrap dropdown attributes.
         */
        if ( $has_children ) {
            $link_attributes .= ' data-bs-toggle="dropdown"';
            $link_attributes .= ' aria-expanded="false"';
        }

        /*
         * Accessible current state.
         */
        if ( $is_active ) {
            $link_attributes .= ' aria-current="page"';
        }

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . $link_attributes . '>';
        $item_output .= $args->link_before;
        $item_output .= apply_filters(
            'the_title',
            $item->title,
            $item->ID
        );
        $item_output .= $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters(
            'walker_nav_menu_start_el',
            $item_output,
            $item,
            $depth,
            $args
        );
    }

    /**
     * Ends the element output.
     *
     * @param string    $output Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int       $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @return void
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ): void
    {
        $output .= "</li>\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @param string    $output Used to append additional content.
     * @param int       $depth  Depth of menu item.
     * @param stdClass $args  An object of wp_nav_menu() arguments.
     * @return void
     */
    public function end_lvl( &$output, $depth = 0, $args = null ): void
    {
        $indent = str_repeat( "\t", $depth );

        $output .= "$indent</ul>\n";
    }
}