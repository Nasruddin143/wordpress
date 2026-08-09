<?php
/**
 * Footer Module
 *
 * Handles the frontend footer.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\View;

defined('ABSPATH') || exit;

class Footer extends Module
{

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        /*
         * Widget Areas.
         */
        add_action(
            'widgets_init',
            [$this, 'register_sidebars']
        );

        /*
         * Register Menu.
         */
        add_action(
            'after_setup_theme',
            [$this, 'register_menus']
        );

        /*
         * Footer.
         */
        add_action(
            'wooshop_footer',
            [$this, 'render']
        );

        /*
         * Footer Sections.
         */
        add_action(
            'wooshop_footer_newsletter',
            [$this, 'newsletter']
        );

        add_action(
            'wooshop_footer_widgets',
            [$this, 'widgets']
        );

        add_action(
            'wooshop_footer_navigation',
            [$this, 'navigation']
        );

        add_action(
            'wooshop_footer_payment_icons',
            [$this, 'payment_icons']
        );

        add_action(
            'wooshop_footer_social',
            [$this, 'social']
        );

        add_action(
            'wooshop_footer_copyright',
            [$this, 'copyright']
        );
    }

    /**
     * Register footer sidebars.
     *
     * @return void
     */
    public function register_sidebars(): void
    {

        $sidebars = [
            'footer-1' => __('Footer Column 1', 'wooshop'),
            'footer-2' => __('Footer Column 2', 'wooshop'),
            'footer-3' => __('Footer Column 3', 'wooshop'),
            'footer-4' => __('Footer Column 4', 'wooshop'),
        ];

        foreach ($sidebars as $id => $name) {

            register_sidebar(
                [
                    'name' => $name,
                    'id' => $id,
                    'description' => sprintf(
                    /* translators: %s: footer column name. */
                        __('Widgets displayed in %s.', 'wooshop'),
                        $name
                    ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget' => '</section>',
                    'before_title' => '<h3 class="widget-title">',
                    'after_title' => '</h3>',
                ]
            );
        }
    }

    /**
     * Render footer.
     *
     * @return void
     */
    public function render(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/footer');
    }

    /**
     * Render newsletter.
     *
     * @return void
     */
    public function newsletter(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/newsletter');
    }

    /**
     * Render widgets.
     *
     * @return void
     */
    public function widgets(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/widgets');
    }

    /**
     * Render navigation.
     *
     * @return void
     */
    public function navigation(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/navigation');
    }

    /**
     * Render payment icons.
     *
     * @return void
     */
    public function payment_icons(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/payment-icons');
    }

    /**
     * Render social links.
     *
     * @return void
     */
    public function social(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/social');
    }

    /**
     * Render copyright.
     *
     * @return void
     */
    public function copyright(): void
    {

        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render('footer/copyright');
    }

    /**
     * Register footer menus.
     *
     * @return void
     */
    public function register_menus(): void
    {
        register_nav_menus(
            [
                'footer' => __('Footer Menu', 'wooshop'),
            ]
        );
    }
}