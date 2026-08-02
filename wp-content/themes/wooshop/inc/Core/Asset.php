<?php
/**
 * Asset Value Object
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Asset {

    /**
     * Asset handle.
     */
    public string $handle;

    /**
     * Relative file.
     */
    public string $file;

    /**
     * Dependencies.
     *
     * @var string[]
     */
    public array $dependencies = [];

    /**
     * Media.
     */
    public string $media = 'all';

    /**
     * Loading strategy.
     */
    public string $strategy = 'defer';

    /**
     * Footer.
     */
    public bool $footer = true;

    /**
     * Asset context.
     */
    public string $context = 'frontend';

    /**
     * Conditions.
     *
     * @var string[]
     */
    public array $conditions = [];

    /**
     * Constructor.
     */
    public function __construct(
        string $handle,
        string $file
    ) {

        $this->handle = $handle;

        $this->file = $file;
    }

}