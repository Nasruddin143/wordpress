<?php
/**
 * Header Actions
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme\Header;

use WooShop\Core\Module;
use WooShop\Core\View;

defined( 'ABSPATH' ) || exit;

class Actions extends Module {

    /**
     * View.
     *
     * @var View
     */
    protected View $view;

    /**
     * Register.
     */
    public function register(): void {

        $this->view = $this->container->get( View::class );

        add_action(
            'wooshop_header_right',
            [ $this, 'render' ],
            20
        );
    }

    /**
     * Render.
     */
    public function render(): void {

        $this->view->render(
            'header/actions'
        );
    }

}