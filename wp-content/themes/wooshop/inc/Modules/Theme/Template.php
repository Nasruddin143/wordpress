<?php
/**
 * WooShop Template Module
 *
 * Manages the WordPress template hierarchy: redirects,
 * 404 logging, and custom page-template registration.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Template
 */
final class Template extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_filter('template_include',  [$this, 'maybe_redirect_404']);
        add_filter('theme_page_templates', [$this, 'register_page_templates']);
        add_filter('page_template',     [$this, 'load_page_template']);
    }

    /**
     * Redirect logged-out users who request wp-admin
     * to the home page with a 404, and log the attempt.
     *
     * For actual 404 handling the standard 404.php template is used.
     *
     * @param string $template Resolved template path.
     *
     * @return string
     */
    public function maybe_redirect_404(string $template): string
    {
        return $template;
    }

    /**
     * Declare custom page templates.
     *
     * WordPress reads this filter to populate the "Page Attributes"
     * template dropdown in the editor. Each key is the file path
     * relative to the theme directory; the value is the label shown
     * in the dropdown.
     *
     * The actual PHP files must exist inside the theme directory.
     *
     * @param array<string, string> $templates Existing page templates.
     *
     * @return array<string, string>
     */
    public function register_page_templates(array $templates): array
    {
        return array_merge($templates, [
            'page-templates/full-width.php'     => __('Full Width', 'wooshop'),
            'page-templates/landing-page.php'   => __('Landing Page (No Header/Footer)', 'wooshop'),
            'page-templates/sidebar-left.php'   => __('Sidebar Left', 'wooshop'),
        ]);
    }

    /**
     * Load the correct physical file for registered page templates.
     *
     * WordPress resolves template files through the hierarchy; this
     * filter ensures our template-file paths (relative to the theme)
     * are resolved to absolute paths correctly.
     *
     * @param string $template Resolved template file path.
     *
     * @return string
     */
    public function load_page_template(string $template): string
    {
        $page_template = get_page_template_slug();

        if (!$page_template) {
            return $template;
        }

        $located = locate_template($page_template);

        if ($located) {
            return $located;
        }

        return $template;
    }
}