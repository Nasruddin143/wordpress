<?php
/**
 * Branding Module
 *
 * Responsible for rendering the site branding.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme\Header;

use WooShop\Core\Module;
use WooShop\Core\View;

defined('ABSPATH') || exit;

class Branding extends Module
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
            'wooshop_header_left',
            [$this,'render'],
            10
        );
    }

    /**
     * Render branding.
     *
     * @return void
     */
    public function render(): void
    {

        $this->view->render('header/branding');
    }
}