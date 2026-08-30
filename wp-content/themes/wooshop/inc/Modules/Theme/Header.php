<?php
/**
 * WooShop Header Module
 *
 * Handles all header-related hooks: markup output,
 * sticky behaviour, body classes, and template parts.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Header
 */
final class Header extends Module
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

        // Output the header markup inside wp_head.
        add_action('wooshop_header', [$this, 'render_header']);

        // Add custom body classes.
        add_filter('body_class', [$this, 'body_classes']);

        // Inject schema markup into <head>.
        add_action('wp_head', [$this, 'schema_markup']);
    }

    /**
     * Render the site header.
     *
     * Called from header.php via:  do_action('wooshop_header');
     *
     * @return void
     */
    public function render_header(): void
    {
        $sticky = $this->config['header']['sticky'] ?? false;

        get_template_part('template-parts/header/header', null, ['sticky' => $sticky,]);
    }

    /**
     * Add theme-specific body classes.
     *
     * @param string[] $classes Existing body classes.
     *
     * @return string[]
     */
    public function body_classes(array $classes): array
    {
        $sticky = $this->config['header']['sticky'] ?? false;

        if ($sticky) {
            $classes[] = 'has-sticky-header';
        }

        if (is_singular()) {
            $classes[] = 'singular';
        }

        return $classes;
    }

    /**
     * Output organization schema JSON-LD in <head>.
     *
     * @return void
     */
    public function schema_markup(): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ];

        echo '<script type="application/ld+json">'
            . wp_json_encode($schema, JSON_UNESCAPED_SLASHES)
            . '</script>' . PHP_EOL;
    }
}