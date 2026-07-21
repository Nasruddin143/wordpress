<?php 

namespace KT\Helpers;

class Bootstrap_NavWalker  extends \Walker_Nav_Menu
{
	/**
	 * Start Level
	 */
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
    $submenu = ($depth > 0) ? ' sub-menu' : '';

		$indent = str_repeat("\t", $depth);
		$submenu_class = ($depth > 0) ? ' sub-menu' : '';

		$output .= "\n$indent<ul class=\"dropdown-menu{$submenu}\" aria-labelledby=\"dropdown-$depth\">\n";
	}

	/**
	 * End Level
	 */
	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start Element
	 */
	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? [] : (array) $item->classes;
		$has_children = in_array('menu-item-has-children', $classes, true);

		$classes[] = 'nav-item';
		if ($has_children && $depth === 0) {
			$classes[] = 'dropdown';
		}

		$class_names = implode(' ', array_map('esc_attr', $classes));
		$item_id = 'menu-item-' . esc_attr($item->ID);

		$output .= $indent . '<li id="' . $item_id . '" class="' . $class_names . '">';

		// Link attributes
		$atts           = '';
		$atts .= !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$atts .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$atts .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$atts .= !empty($item->url) ? ' href="' . esc_url($item->url) . '"' : '';

		$is_active = in_array('current-menu-item', $classes, true) ||
		             in_array('current-menu-ancestor', $classes, true);

		$link_class = ($depth === 0) ? 'nav-link' : 'dropdown-item';
		$link_class .= $is_active ? ' active' : '';

		if ($has_children && $depth === 0) {
			$atts .= ' class="' . $link_class . ' dropdown-toggle"';
			$atts .= ' data-bs-toggle="dropdown"';
			$atts .= ' aria-expanded="false"';
			$atts .= ' role="button"';
		} else {
			$atts .= ' class="' . $link_class . '"';
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $atts . '>';
		$item_output .= $args->link_before . esc_html($item->title) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	/**
	 * End Element
	 */
	public function end_el(&$output, $item, $depth = 0, $args = null)
	{
		$output .= "</li>\n";
	}
}
