<?php
/**
 * WooShop Footer Module
 *
 * Handles all footer-related hooks: markup output,
 * layout variants, body classes, and template parts.
 *
 * Mirror of WooShop\Modules\Theme\Header.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Footer
 */
final class Footer extends Module
{
    /**
     * Cached theme config.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        // Pull theme config once via the container.
        $this->config = $this->container->get('config')->get('theme');

        // Output the footer markup via the wooshop_footer action.
        add_action('wooshop_footer', [$this, 'render_footer']);

        // Add footer-specific body classes.
        add_filter('body_class', [$this, 'body_classes']);

        // Inject Organization schema JSON-LD — complements
        // the WebSite schema output by the Header module.
        add_action('wp_footer', [$this, 'schema_markup'], 99);
    }

    /**
     * Render the site footer.
     *
     * Called from footer.php via:  do_action('wooshop_footer');
     *
     * @return void
     */
    public function render_footer(): void
    {
        $layout = $this->config['footer']['layout'] ?? 'standard';
        $columns = $this->config['footer']['columns'] ?? 3;

        get_template_part('template-parts/footer/footer', null, [
            'layout' => $layout,
            'columns' => $columns,
        ]);
    }

    /**
     * Add footer-specific body classes.
     *
     * @param string[] $classes Existing body classes.
     *
     * @return string[]
     */
    public function body_classes(array $classes): array
    {
        $layout = $this->config['footer']['layout'] ?? 'standard';
        $columns = (int)($this->config['footer']['columns'] ?? 3);

        // Signal which footer layout is active to CSS.
        $classes[] = 'footer-layout--' . sanitize_html_class($layout);

        // Signal whether footer widget areas are in use.
        $has_widgets = false;
        for ($i = 1; $i <= $columns; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                $has_widgets = true;
                break;
            }
        }

        $classes[] = $has_widgets ? 'has-footer-widgets' : 'no-footer-widgets';

        return $classes;
    }

    /**
     * Output Organization schema JSON-LD in wp_footer.
     *
     * Complements the WebSite schema injected by Header::schema_markup().
     *
     * @return void
     */
    public function schema_markup(): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'logo' => has_custom_logo()
                ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')
                : null,
        ];

        // Remove null values before encoding.
        $schema = array_filter($schema, static fn(mixed $v): bool => $v !== null);

        echo '<script type="application/ld+json">'
            . wp_json_encode($schema, JSON_UNESCAPED_SLASHES)
            . '</script>' . PHP_EOL;
    }
}