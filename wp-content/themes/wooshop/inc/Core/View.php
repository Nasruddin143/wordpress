<?php
/**
 * View Renderer
 *
 * Responsible only for rendering PHP templates.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class View
{

    /**
     * Template loader.
     *
     * @var TemplateLoader
     */
    protected TemplateLoader $loader;

    /**
     * Constructor.
     *
     * @param TemplateLoader $loader Template loader.
     */
    public function __construct(
        TemplateLoader $loader
    ) {
        $this->loader = $loader;
    }

    /**
     * Render template.
     *
     * @param string $template Template name.
     * @param array  $args     Template arguments.
     *
     * @return void
     */
    public function render(
        string $template,
        array $args = []
    ): void {

        $this->loader->render(
            $template,
            $args
        );
    }
}