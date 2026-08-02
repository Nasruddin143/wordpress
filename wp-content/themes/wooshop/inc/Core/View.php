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

class View {

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
    public function __construct( TemplateLoader $loader ) {
        $this->loader = $loader;
    }

    /**
     * Render a template.
     *
     * @param string $template Template name.
     * @param array  $data     Data available to the template.
     *
     * @return void
     */
    public function render( string $template, array $data = [] ): void {
        echo $this->renderToString( $template, $data );
    }

    /**
     * Render a template and return HTML.
     *
     * @param string $template Template name.
     * @param array  $data     Template data.
     *
     * @return string
     */
    public function renderToString( string $template, array $data = [] ): string {

        $file = $this->loader->locate( $template );

        if ( ! $file ) {
            return '';
        }

        ob_start();

        include $file;

        return (string) ob_get_clean();
    }

    /**
     * Check if a template exists.
     *
     * @param string $template Template name.
     *
     * @return bool
     */
    public function exists( string $template ): bool {
        return $this->loader->exists( $template );
    }
}