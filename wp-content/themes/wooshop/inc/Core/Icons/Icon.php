<?php
/**
 * Icon Helper
 *
 * @package WooShop
 */

namespace WooShop\Core\Icons;

defined('ABSPATH') || exit;

use WooShop\Core\Container;

class Icon
{

    /**
     * Container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct(Container $container)
    {

        $this->container = $container;
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

        return $this->container
            ->get(Manager::class)
            ->render($name, $args);
    }
}