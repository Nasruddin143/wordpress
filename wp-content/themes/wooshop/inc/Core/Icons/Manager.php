<?php
/**
 * Icon Manager
 *
 * Loads and renders SVG icons.
 *
 * @package WooShop
 */

namespace WooShop\Core\Icons;

defined('ABSPATH') || exit;

class Manager
{

    /**
     * SVG directory.
     *
     * @var string
     */
    protected string $directory;

    /**
     * Loaded icons.
     *
     * @var array
     */
    protected array $cache = [];

    /**
     * Constructor.
     *
     * @param string $directory SVG directory.
     */
    public function __construct(string $directory)
    {

        $this->directory = trailingslashit($directory);
    }

    /**
     * Render icon.
     *
     * @param string $name Icon name.
     * @param array $args Icon arguments.
     *
     * @return string
     */
    public function render(string $name, array $args = []): string
    {

        $svg = $this->get($name);

        if (empty($svg)) {
            return '';
        }

        $defaults = [
            'class' => 'ws-icon',
            'size' => 20,
            'label' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $attributes = sprintf(
            'class="%s" width="%d" height="%d"',
            esc_attr($args['class']),
            absint($args['size']),
            absint($args['size'])
        );

        if (!empty($args['label'])) {

            $attributes .= sprintf(
                ' role="img" aria-label="%s"',
                esc_attr($args['label'])
            );

        } else {

            $attributes .= ' aria-hidden="true"';

        }

        return preg_replace(
            '/<svg\b([^>]*)>/',
            '<svg$1 ' . $attributes . '>',
            $svg,
            1
        );
    }

    /**
     * Load SVG.
     *
     * @param string $name Icon name.
     *
     * @return string
     */
    protected function get(string $name): string
    {

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $file = $this->directory . $name . '.svg';

        if (!file_exists($file)) {
            return '';
        }

        $svg = file_get_contents($file);

        if (false === $svg) {
            return '';
        }

        $this->cache[$name] = trim($svg);

        return $this->cache[$name];
    }
}