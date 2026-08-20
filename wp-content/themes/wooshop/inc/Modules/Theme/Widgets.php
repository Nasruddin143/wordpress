<?php
/**
 * WooShop Widgets Module
 *
 * Registers and configures WooShop theme widget functionality.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Widgets
{
    /**
     * Register the widgets' module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'widgets_init',
            [$this, 'register_widgets']
        );

        add_filter(
            'widget_display_callback',
            [$this, 'filter_widget_display'],
            10,
            3
        );

        add_filter(
            'dynamic_sidebar_params',
            [$this, 'filter_sidebar_params']
        );
    }

    /**
     * Register WooShop widget areas.
     *
     * Widget areas themselves are registered by the Sidebars
     * module. This method remains the dedicated extension point
     * for widgets that belong specifically to the theme.
     *
     * @return void
     */
    public function register_widgets(): void
    {
        /*
         * Theme-specific widget registration point.
         *
         * WooCommerce widgets remain managed by WooCommerce.
         */
    }

    /**
     * Filter widget display.
     *
     * Prevents invalid or empty widget objects from being
     * rendered by the theme.
     *
     * @param array<string, mixed>|false $instance Widget instance.
     * @param array<string, mixed>       $widget   Widget settings.
     * @param mixed                      $args     Sidebar arguments.
     *
     * @return array<string, mixed>|false
     */
    public function filter_widget_display(
        array|false $instance,
        array $widget,
        mixed $args
    ): array|false {
        if ($instance === false) {
            return false;
        }

        return $instance;
    }

    /**
     * Add WooShop classes to widget wrappers.
     *
     * @param array<string, mixed> $params Sidebar widget parameters.
     *
     * @return array<int, array<string, mixed>>
     */
    public function filter_sidebar_params(
        array $params
    ): array {
        foreach ($params as &$param) {
            if (
                isset($param['before_widget'])
                && is_string($param['before_widget'])
            ) {
                $param['before_widget'] = str_replace(
                    'class="widget',
                    'class="widget ws-widget',
                    $param['before_widget']
                );
            }
        }

        unset($param);

        return $params;
    }
}