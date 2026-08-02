<?php
/**
 * Asset Manager
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

class AssetManager
{

    /**
     * Theme path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Theme URI.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Registered styles.
     *
     * @var array<string,array>
     */
    protected array $styles = [];

    /**
     * Registered scripts.
     *
     * @var array<string,array>
     */
    protected array $scripts = [];


    /**
     * Register Editor styles.
     *
     * @var array<string,array>
     */
    protected array $editorStyles = [];

    /**
     * Register Editor scripts.
     *
     * @var array<string,array>
     */
    protected array $editorScripts = [];

    /**
     * Constructor.
     */
    public function __construct()
    {

        $this->path = get_stylesheet_directory();
        $this->uri = get_stylesheet_directory_uri();
    }

    /**
     * Register stylesheet.
     */
    public function registerStyle(string $handle, string $file, array $deps = [], string $media = 'all'): void
    {

        $this->styles[$handle] = [

            'file' => $file,

            'deps' => $deps,

            'media' => $media,

        ];
    }

    /**
     * Register script.
     */
    public function registerScript(string $handle, string $file, array $deps = [], string $strategy = 'defer', bool $footer = true): void
    {

        $this->scripts[$handle] = [

            'file' => $file,

            'deps' => $deps,

            'strategy' => $strategy,

            'footer' => $footer,

        ];
    }

    /**
     * Enqueue all registered styles.
     */
    public function enqueueStyles(): void
    {

        foreach ($this->styles as $handle => $style) {

            $file = $this->file($style['file']);

            if (!file_exists($file)) {
                continue;
            }

            wp_enqueue_style(

                $handle,

                $this->url($style['file']),

                $style['deps'],

                $this->version($file),

                $style['media']

            );
        }
    }

    /**
     * Enqueue all registered scripts.
     */
    public function enqueueScripts(): void
    {

        foreach ($this->scripts as $handle => $script) {

            $file = $this->file($script['file']);

            if (!file_exists($file)) {
                continue;
            }

            wp_enqueue_script(

                $handle,

                $this->url($script['file']),

                $script['deps'],

                $this->version($file),

                [

                    'strategy' => $script['strategy'],

                    'in_footer' => $script['footer'],

                ]

            );
        }
    }

    /**
     * Enqueue all assets.
     */
    public function enqueue(): void
    {

        $this->enqueueStyles();

        $this->enqueueScripts();
    }

    /**
     * Absolute file path.
     */
    public function file(string $file): string
    {

        return trailingslashit($this->path) . ltrim($file, '/');
    }

    /**
     * Asset URL.
     */
    public function url(string $file): string
    {

        return trailingslashit($this->uri) . ltrim($file, '/');
    }

    /**
     * Version using filemtime().
     */
    public function version(string $file): string
    {

        $time = filemtime($file);

        if (false === $time) {

            return wp_get_theme()->get('Version');

        }

        return (string)$time;
    }

    /**
     * Get registered style.
     */
    public function getStyle(string $handle): ?array
    {

        return $this->styles[$handle] ?? null;
    }

    /**
     * Get registered script.
     */
    public function getScript(string $handle): ?array
    {

        return $this->scripts[$handle] ?? null;
    }

    /**
     * Check style exists.
     */
    public function hasStyle(string $handle): bool
    {

        return isset(
            $this->styles[$handle]
        );
    }

    /**
     * Check script exists.
     */
    public function hasScript(string $handle): bool
    {

        return isset(
            $this->scripts[$handle]
        );
    }

    /**
     * Remove Style.
     */
    public function removeStyle(string $handle): void
    {

        unset(
            $this->styles[$handle]
        );
    }

    /**
     * Remove Script.
     */
    public function removeScript(string $handle): void
    {

        unset(
            $this->scripts[$handle]
        );
    }

    /**
     * Register Editor Style.
     */
    public function registerEditorStyle(
        string $handle,
        string $file,
        array $deps = []
    ): void {

        $this->editorStyles[ $handle ] = [

            'file' => $file,

            'deps' => $deps,

        ];
    }

    /**
     * Register Editor Script.
     */
    public function registerEditorScript(
        string $handle,
        string $file,
        array $deps = []
    ): void {

        $this->editorScripts[ $handle ] = [

            'file' => $file,

            'deps' => $deps,

        ];
    }

    /**
     * Enqueue Editor Assets.
     */
    public function enqueueEditor(): void {

        foreach ( $this->editorStyles as $handle => $style ) {

            $file = $this->file( $style['file'] );

            if ( ! file_exists( $file ) ) {
                continue;
            }

            wp_enqueue_style(

                $handle,

                $this->url( $style['file'] ),

                $style['deps'],

                $this->version( $file )

            );
        }

        foreach ( $this->editorScripts as $handle => $script ) {

            $file = $this->file( $script['file'] );

            if ( ! file_exists( $file ) ) {
                continue;
            }

            wp_enqueue_script(

                $handle,

                $this->url( $script['file'] ),

                $script['deps'],

                $this->version( $file ),

                true

            );
        }
    }
}