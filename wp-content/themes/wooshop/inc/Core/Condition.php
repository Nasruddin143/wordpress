<?php
/**
 * Theme Condition Resolver
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

class Condition
{

    /**
     * Registered conditions.
     *
     * @var array<string,callable>
     */
    protected array $conditions = [];

    /**
     * Constructor.
     */
    public function __construct()
    {

        $this->register_defaults();
    }

    /**
     * Register default conditions.
     */
    protected function register_defaults(): void
    {

        $this->add('front_page', 'is_front_page');

        $this->add('home', 'is_home');

        $this->add('singular', 'is_singular');

        $this->add('page', 'is_page');

        $this->add('single', 'is_single');

        $this->add('archive', 'is_archive');

        $this->add('search', 'is_search');

        $this->add('404', 'is_404');

        $this->add(
            'logged_in',
            'is_user_logged_in'
        );

        $this->add(
            'logged_out',
            static function (): bool {

                return !is_user_logged_in();

            }
        );

        $this->add(
            'admin',
            'is_admin'
        );
    }

    /**
     * Register condition.
     */
    public function add(string $name, callable $callback): void
    {

        $this->conditions[$name] = $callback;
    }

    /**
     * Determine whether a condition passes.
     */
    public function check(string $condition): bool
    {

        if (!isset(
            $this->conditions[$condition]
        )) {

            return false;
        }

        return (bool)call_user_func(
            $this->conditions[$condition]
        );
    }

    /**
     * Determine whether every condition passes.
     */
    public function matches(array $conditions): bool
    {

        foreach ($conditions as $condition) {

            if (!$this->check($condition)) {

                return false;
            }
        }

        return true;
    }

    /**
     * Check if a condition exists.
     */
    public function has(string $condition): bool
    {

        return isset(
            $this->conditions[$condition]
        );
    }

    /**
     * Global assets.
     */
    public function global(): bool {

        return true;
    }

    /**
     * Editor assets.
     */
    public function editor(): bool {

        return is_admin();
    }

    /**
     * Slider assets.
     */
    public function slider(): bool {

        return is_front_page();
    }
}