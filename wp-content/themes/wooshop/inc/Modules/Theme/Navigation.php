<?php
/**
 * Navigation Module
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme\Header;

use WooShop\Core\Module;
use WooShop\Core\View;

defined( 'ABSPATH' ) || exit;

class Navigation extends Module
{

    /**
     * View renderer.
     *
     * @var View
     */
    protected View $view;

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        $this->view = $this->container->get(View::class);

        add_action(
                'after_setup_theme',
                [$this, 'register_menus']
        );

        add_action(
                'wooshop_header_center',
                [$this,'render'],
                10
        );
    }

    /**
     * Register theme menus.
     *
     * @return void
     */
    public function register_menus(): void
    {

        register_nav_menus(
                [
                        'primary' => __('Primary Menu', 'wooshop'),
                        'topbar' => __('Top Bar Menu', 'wooshop'),
                        'mobile' => __('Mobile Menu', 'wooshop'),
                        'footer' => __('Footer Menu', 'wooshop'),
                ]
        );
    }

    /**
     * Render navigation.
     *
     * @return void
     */
    public function render(): void
    {

        $this->view->render('header/navigation');
    }
}