<?php
/**
 * WooShop Localization Module
 *
 * Handles theme translation and localization setup.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Localization
{
    /**
     * WooShop text domain.
     *
     * @var string
     */
    private string $text_domain = 'wooshop';

    /**
     * Register the localization module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'load_textdomain']
        );
    }

    /**
     * Load the WooShop translation files.
     *
     * WordPress 6.7+ automatically loads translations for
     * themes hosted on WordPress.org. The explicit loader is
     * retained for custom or locally distributed WooShop builds.
     *
     * @return void
     */
    public function load_textdomain(): void
    {
        load_theme_textdomain(
            $this->text_domain,
            get_template_directory() . '/languages'
        );
    }
}