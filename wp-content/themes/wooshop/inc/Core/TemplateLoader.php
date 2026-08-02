<?php
/**
 * Template Loader
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class TemplateLoader {

    /**
     * Template search paths.
     *
     * Ordered by priority.
     *
     * @var array<int,string>
     */
    protected array $paths = [];

    /**
     * Constructor.
     */
    public function __construct() {

        $this->registerDefaultPaths();
    }

    /**
     * Register default template paths.
     *
     * @return void
     */
    protected function registerDefaultPaths(): void {

        $stylesheet = trailingslashit( get_stylesheet_directory() );
        $template   = trailingslashit( get_template_directory() );

        $this->paths = [

            // Child theme.
            $stylesheet . 'template-parts/',

            // Parent theme.
            $template . 'template-parts/',

            // Parent templates.
            $template . 'templates/',

        ];

        /**
         * Filter template search paths.
         */
        $this->paths = apply_filters(
            'wooshop/template_paths',
            $this->paths
        );
    }

    /**
     * Locate a template.
     *
     * Example:
     * content/product-card
     *
     * @param string $template Template slug.
     *
     * @return string|false
     */
    public function locate( string $template ) {

        $template = ltrim( $template, '/' );

        foreach ( $this->paths as $path ) {

            $file = trailingslashit( $path ) . $template . '.php';

            if ( file_exists( $file ) ) {
                return $file;
            }
        }

        return false;
    }

    /**
     * Determine whether a template exists.
     *
     * @param string $template Template slug.
     *
     * @return bool
     */
    public function exists( string $template ): bool {

        return (bool) $this->locate( $template );
    }

    /**
     * Register an additional template path.
     *
     * Higher priority than defaults.
     *
     * @param string $path Directory path.
     *
     * @return void
     */
    public function addPath( string $path ): void {

        $path = trailingslashit( $path );

        if ( ! in_array( $path, $this->paths, true ) ) {

            array_unshift(
                $this->paths,
                $path
            );
        }
    }

    /**
     * Return registered template paths.
     *
     * @return array<int,string>
     */
    public function getPaths(): array {

        return $this->paths;
    }

    /**
     * Locate a component.
     *
     * @param string $component Component name.
     *
     * @return string|false
     */
    public function locateComponent( string $component ) {

        return $this->locate(
            'components/' . $component
        );
    }

    /**
     * Locate a layout.
     *
     * @param string $layout Layout name.
     *
     * @return string|false
     */
    public function locateLayout( string $layout ) {

        foreach ( $this->paths as $path ) {

            $file = trailingslashit( dirname( $path ) )
                . 'templates/'
                . $layout
                . '.php';

            if ( file_exists( $file ) ) {
                return $file;
            }
        }

        return false;
    }
}