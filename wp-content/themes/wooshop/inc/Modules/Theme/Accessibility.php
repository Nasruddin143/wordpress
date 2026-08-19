<?php
/**
 * WooShop Accessibility Module
 *
 * Provides accessibility improvements for the WooShop theme,
 * including navigation landmarks, accessible document titles,
 * skip links, focus handling, and useful accessibility attributes.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use stdClass;
use WooShop\Core\Module;
use WP_Post;

defined('ABSPATH') || exit;

/**
 * Class Accessibility
 *
 * Handles WooShop accessibility-related functionality.
 */
final class Accessibility extends Module {

    /**
     * Register accessibility functionality.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_body_open',
            [$this, 'render_skip_link'],
            5
        );

        add_filter(
            'nav_menu_link_attributes',
            [$this, 'filter_menu_link_attributes'],
            10,
            4
        );

        add_filter(
            'comment_form_defaults',
            [$this, 'filter_comment_form_defaults']
        );

        add_filter(
            'wp_page_menu_args',
            [$this, 'filter_page_menu_args']
        );

        add_filter(
            'get_search_form',
            [$this, 'filter_search_form']
        );

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_accessibility_assets'],
            30
        );
    }

    /**
     * Render the primary skip link.
     *
     * Allows keyboard and assistive-technology users to bypass
     * repetitive navigation and move directly to main content.
     *
     * @return void
     */
    public function render_skip_link(): void {

        ?>
        <a
            class="visually-hidden-focusable ws-skip-link"
            href="#primary"
        >
            <?php
            echo esc_html__(
                'Skip to content',
                'wooshop'
            );
            ?>
        </a>
        <?php
    }

    /**
     * Add accessibility attributes to navigation links.
     *
     * @param array<string, mixed> $atts       Navigation attributes.
     * @param WP_Post              $item       Menu item.
     * @param stdClass             $args       Menu arguments.
     * @param int                  $depth      Menu depth.
     *
     * @return array<string, mixed>
     */
    public function filter_menu_link_attributes(
        array    $atts,
        WP_Post  $item,
        stdClass $args,
        int      $depth
    ): array {

        if (
            isset($item->current)
            && $item->current
        ) {
            $atts['aria-current'] = 'page';
        }

        return $atts;
    }

    /**
     * Improve comment form accessibility.
     *
     * @param array<string, mixed> $defaults Comment form defaults.
     *
     * @return array<string, mixed>
     */
    public function filter_comment_form_defaults(
        array $defaults
    ): array {

        if (!isset($defaults['comment_field'])) {
            return $defaults;
        }

        $defaults['comment_field'] = sprintf(
            '<p class="comment-form-comment">
				<label for="comment">%s%s</label>
				<textarea
					id="comment"
					name="comment"
					cols="45"
					rows="8"
					required
				></textarea>
			</p>',
            esc_html__(
                'Comment',
                'wooshop'
            ),
            '<span class="required" aria-hidden="true">*</span>'
        );

        return $defaults;
    }

    /**
     * Add an accessible menu container to WordPress page menus.
     *
     * @param array<string, mixed> $args Page menu arguments.
     *
     * @return array<string, mixed>
     */
    public function filter_page_menu_args(
        array $args
    ): array {

        $args['menu_class'] = 'page-menu';

        return $args;
    }

    /**
     * Add an accessible label to the WordPress search form.
     *
     * @param string $form Search form HTML.
     *
     * @return string
     */
    public function filter_search_form(
        string $form
    ): string {

        if (
            false !== strpos(
                $form,
                'aria-label='
            )
        ) {
            return $form;
        }

        $form = str_replace(
            '<form',
            '<form aria-label="' .
            esc_attr__(
                'Site search',
                'wooshop'
            ) .
            '"',
            $form
        );

        return $form;
    }

    /**
     * Enqueue accessibility component styles.
     *
     * The component stylesheet is loaded independently of the
     * main WooShop stylesheet.
     *
     * @return void
     */
    public function enqueue_accessibility_assets(): void {

        $src = get_theme_file_uri(
            'assets/build/css/components/accessibility.min.css'
        );

        $path = get_theme_file_path(
            'assets/build/css/components/accessibility.min.css'
        );

        if (!is_file($path)) {
            return;
        }

        wp_enqueue_style(
            'wooshop-accessibility',
            $src,
            array(),
            (string) filemtime($path)
        );
    }
}