<?php
/**
 * Template Loader
 *
 * Handles WooShop template resolution.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class TemplateLoader
{

    /**
     * Parent theme path.
     *
     * @var string
     */
    protected string $parent_path;

    /**
     * Child theme path.
     *
     * @var string
     */
    protected string $child_path;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parent_path = get_template_directory();

        $this->child_path = get_stylesheet_directory();
    }

    /**
     * Locate template.
     *
     * Child theme takes priority.
     *
     * @param string $template Template name.
     *
     * @return string|false
     */
    public function locate( string $template )
    {
        $template = ltrim( $template, '/' );

        /*
         * WooShop template-parts.
         */
        $template = 'template-parts/' . $template;

        /*
         * Child theme.
         */
        $child_template = trailingslashit( $this->child_path )
            . $template
            . '.php';

        if ( file_exists( $child_template ) ) {
            return $child_template;
        }

        /*
         * Parent theme.
         */
        $parent_template = trailingslashit( $this->parent_path )
            . $template
            . '.php';

        if ( file_exists( $parent_template ) ) {
            return $parent_template;
        }

        return false;
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

        $file = $this->locate( $template );

        if ( ! $file ) {
            return;
        }

        /*
         * Make template arguments available as variables.
         */
        if ( ! empty( $args ) ) {
            extract(
                $args,
                EXTR_SKIP
            );
        }

        include $file;
    }
}