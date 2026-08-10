<?php

/**
 * WooCommerce Variation Swatch Admin
 *
 * Handles variation swatch fields for WooCommerce
 * global attribute terms.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

class VariationSwatchAdmin extends Module
{

    /**
     * Swatch type field name.
     *
     * @var string
     */
    private const TYPE_FIELD = 'wooshop_swatch_type';

    /**
     * Swatch color field name.
     *
     * @var string
     */
    private const COLOR_FIELD = 'wooshop_swatch_color';

    /**
     * Swatch image field name.
     *
     * @var string
     */
    private const IMAGE_FIELD = 'wooshop_swatch_image';

    /**
     * Nonce action.
     *
     * @var string
     */
    private const NONCE_ACTION = 'wooshop_save_variation_swatch';

    /**
     * Nonce field.
     *
     * @var string
     */
    private const NONCE_FIELD = 'wooshop_variation_swatch_nonce';


    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        if (!class_exists('WooCommerce')) {
            return;
        }

        if (!is_admin()) {
            return;
        }

        add_action(
                'admin_enqueue_scripts',
                [$this, 'enqueue_assets']
        );

        add_action(
                'admin_init',
                [$this, 'register_hooks']
        );
    }


    /**
     * Register taxonomy hooks.
     *
     * @return void
     */
    public function register_hooks(): void
    {

        $taxonomies = $this->get_attribute_taxonomies();

        foreach ($taxonomies as $taxonomy) {

            add_action(
                    "{$taxonomy}_add_form_fields",
                    [$this, 'add_fields']
            );

            add_action(
                    "{$taxonomy}_edit_form_fields",
                    [$this, 'edit_fields'],
                    10,
                    2
            );

            add_action(
                    "created_{$taxonomy}",
                    [$this, 'save_fields']
            );

            add_action(
                    "edited_{$taxonomy}",
                    [$this, 'save_fields']
            );

            add_filter(
                    'manage_product_cat_custom_column',
                    [ $this, 'render_term_column' ],
                    10,
                    3
            );

            add_action(
                    'admin_init',
                    [ $this, 'register_term_columns' ]
            );
        }
    }


    /**
     * Get WooCommerce global attribute taxonomies.
     *
     * @return array
     */
    private function get_attribute_taxonomies(): array
    {

        if (!function_exists('wc_get_attribute_taxonomies')) {
            return [];
        }

        $taxonomies = [];

        foreach (wc_get_attribute_taxonomies() as $attribute) {

            if (empty($attribute->attribute_name)) {
                continue;
            }

            $taxonomies[] = wc_attribute_taxonomy_name(
                    $attribute->attribute_name
            );
        }

        return array_unique(
                $taxonomies
        );
    }


    /**
     * Add fields to the "Add New Attribute Term" screen.
     *
     * @return void
     */
    public function add_fields(): void
    {
        ?>
        <div class="form-field wooshop-swatch-field">

            <label for="<?php echo esc_attr(self::TYPE_FIELD); ?>">
                <?php esc_html_e('Swatch Type', 'wooshop'); ?>
            </label>

            <select name="<?php echo esc_attr(self::TYPE_FIELD); ?>" id="<?php echo esc_attr(self::TYPE_FIELD); ?>">
                <option value="label">
                    <?php esc_html_e('Label', 'wooshop'); ?>
                </option>

                <option value="color">
                    <?php esc_html_e('Color', 'wooshop'); ?>
                </option>

                <option value="image">
                    <?php esc_html_e('Image', 'wooshop'); ?>
                </option>
            </select>

            <p class="description">
                <?php
                esc_html_e(
                        'Choose how this attribute term should appear as a variation swatch.',
                        'wooshop'
                );
                ?>
            </p>

        </div>

        <div class="form-field wooshop-swatch-color-field">

            <label for="<?php echo esc_attr(self::COLOR_FIELD); ?>">
                <?php esc_html_e('Swatch Color', 'wooshop'); ?>
            </label>

            <input type="color" name="<?php echo esc_attr(self::COLOR_FIELD); ?>"
                   id="<?php echo esc_attr(self::COLOR_FIELD); ?>" value="#ffffff" class="wooshop-color-picker"/>

            <p class="description">
                <?php
                esc_html_e(
                        'Choose the color used for this variation swatch.',
                        'wooshop'
                );
                ?>
            </p>

        </div>

        <div class="form-field wooshop-swatch-image-field">

            <label for="<?php echo esc_attr(self::IMAGE_FIELD); ?>">
                <?php esc_html_e('Swatch Image', 'wooshop'); ?>
            </label>

            <div class="wooshop-swatch-image-control">

                <input type="hidden" name="<?php echo esc_attr(self::IMAGE_FIELD); ?>"
                       id="<?php echo esc_attr(self::IMAGE_FIELD); ?>" value=""/>

                <div class="wooshop-swatch-image-preview"></div>

                <button type="button" class="button wooshop-upload-swatch-image">
                    <?php esc_html_e('Select Image', 'wooshop'); ?>
                </button>

                <button type="button" class="button wooshop-remove-swatch-image">
                    <?php esc_html_e('Remove Image', 'wooshop'); ?>
                </button>

            </div>

            <p class="description">
                <?php
                esc_html_e(
                        'Select an image from the WordPress Media Library.',
                        'wooshop'
                );
                ?>
            </p>

        </div>

        <div class="form-field wooshop-swatch-preview-field">

            <label>
                <?php esc_html_e('Swatch Preview', 'wooshop'); ?>
            </label>

            <div
                    class="wooshop-swatch-preview"
                    aria-live="polite">
                <span class="wooshop-swatch-preview-label">
                    <?php esc_html_e('Preview', 'wooshop'); ?>
                </span>
            </div>

            <p class="description">
                <?php
                esc_html_e(
                        'Preview how this variation swatch will appear.',
                        'wooshop'
                );
                ?>
            </p>

        </div>

        <?php

        wp_nonce_field(
                self::NONCE_ACTION,
                self::NONCE_FIELD
        );
    }


    /**
     * Edit existing term fields.
     *
     * @param \WP_Term $term Current term.
     * @param string $taxonomy Taxonomy name.
     *
     * @return void
     */
    public function edit_fields(
            $term,
            string $taxonomy
    ): void
    {

        $term_id = (int)$term->term_id;

        $meta = $this->get_meta_module();

        $type = $meta->get_type(
                $term_id
        );

        $color = $meta->get_color(
                $term_id
        );

        $image = $meta->get_image(
                $term_id
        );

        $image_url = '';

        if ($image) {

            $image_url = wp_get_attachment_image_url(
                    $image,
                    'thumbnail'
            );
        }

        ?>
        <tr class="form-field wooshop-swatch-field">

            <th scope="row">
                <label for="<?php echo esc_attr(self::TYPE_FIELD); ?>">
                    <?php esc_html_e('Swatch Type', 'wooshop'); ?>
                </label>
            </th>

            <td>

                <select name="<?php echo esc_attr(self::TYPE_FIELD); ?>" id="<?php echo esc_attr(self::TYPE_FIELD); ?>">

                    <option value="label" <?php selected($type, 'label'); ?>>
                        <?php esc_html_e('Label', 'wooshop'); ?>
                    </option>

                    <option value="color" <?php selected($type, 'color'); ?>>
                        <?php esc_html_e('Color', 'wooshop'); ?>
                    </option>

                    <option value="image" <?php selected($type, 'image'); ?>>
                        <?php esc_html_e('Image', 'wooshop'); ?>
                    </option>

                </select>

                <p class="description">
                    <?php
                    esc_html_e(
                            'Choose how this attribute term should appear as a variation swatch.',
                            'wooshop'
                    );
                    ?>
                </p>

            </td>

        </tr>

        <tr class="form-field wooshop-swatch-color-field">

            <th scope="row">
                <label for="<?php echo esc_attr(self::COLOR_FIELD); ?>">
                    <?php esc_html_e('Swatch Color', 'wooshop'); ?>
                </label>
            </th>

            <td>

                <input type="color" name="<?php echo esc_attr(self::COLOR_FIELD); ?>"
                       id="<?php echo esc_attr(self::COLOR_FIELD); ?>"
                       value="<?php echo esc_attr($color ?: '#ffffff'); ?>"
                       class="wooshop-color-picker"/>

                <p class="description">
                    <?php
                    esc_html_e(
                            'Choose the color used for this variation swatch.',
                            'wooshop'
                    );
                    ?>
                </p>

            </td>

        </tr>

        <tr class="form-field wooshop-swatch-image-field">

            <th scope="row">
                <label for="<?php echo esc_attr(self::IMAGE_FIELD); ?>">
                    <?php esc_html_e('Swatch Image', 'wooshop'); ?>
                </label>
            </th>

            <td>

                <div class="wooshop-swatch-image-control">

                    <input type="hidden" name="<?php echo esc_attr(self::IMAGE_FIELD); ?>"
                           id="<?php echo esc_attr(self::IMAGE_FIELD); ?>" value="<?php echo esc_attr($image); ?>"/>

                    <div class="wooshop-swatch-image-preview">

                        <?php if ($image_url): ?>

                            <img src="<?php echo esc_url($image_url); ?>" alt=""/>

                        <?php endif; ?>

                    </div>

                    <button type="button" class="button wooshop-upload-swatch-image">
                        <?php esc_html_e('Select Image', 'wooshop'); ?>
                    </button>

                    <button type="button" class="button wooshop-remove-swatch-image">
                        <?php esc_html_e('Remove Image', 'wooshop'); ?>
                    </button>

                </div>

                <p class="description">
                    <?php
                    esc_html_e(
                            'Select an image from the WordPress Media Library.',
                            'wooshop'
                    );
                    ?>
                </p>

            </td>

        </tr>

        <tr class="form-field wooshop-swatch-preview-field">

            <th scope="row">
                <label>
                    <?php esc_html_e('Swatch Preview', 'wooshop'); ?>
                </label>
            </th>

            <td>

                <div
                        class="wooshop-swatch-preview"
                        aria-live="polite">
                    <span class="wooshop-swatch-preview-label">
                        <?php echo esc_html($term->name); ?>
                    </span>
                </div>

                <p class="description">
                    <?php
                    esc_html_e(
                            'Preview how this variation swatch will appear.',
                            'wooshop'
                    );
                    ?>
                </p>

            </td>

        </tr>

        <?php

        wp_nonce_field(
                self::NONCE_ACTION,
                self::NONCE_FIELD
        );
    }


    /**
     * Save term fields.
     *
     * @param int $term_id Term ID.
     *
     * @return void
     */
    public function save_fields(
            int $term_id
    ): void
    {

        if (
                !isset(
                        $_POST[self::NONCE_FIELD]
                )
        ) {
            return;
        }

        $nonce = sanitize_text_field(
                wp_unslash(
                        $_POST[self::NONCE_FIELD]
                )
        );

        if (
                !wp_verify_nonce(
                        $nonce,
                        self::NONCE_ACTION
                )
        ) {
            return;
        }

        if (
                !current_user_can(
                        'manage_product_terms'
                )
        ) {
            return;
        }

        $meta = $this->get_meta_module();

        /*
         * Swatch type.
         */
        $type = isset(
                $_POST[self::TYPE_FIELD]
        )
                ? sanitize_key(
                        wp_unslash(
                                $_POST[self::TYPE_FIELD]
                        )
                )
                : 'label';

        $meta->save_type(
                $term_id,
                $type
        );

        /*
         * Swatch color.
         */
        $color = isset(
                $_POST[self::COLOR_FIELD]
        )
                ? sanitize_text_field(
                        wp_unslash(
                                $_POST[self::COLOR_FIELD]
                        )
                )
                : '';

        $meta->save_color(
                $term_id,
                $color
        );

        /*
         * Swatch image.
         */
        $image = isset(
                $_POST[self::IMAGE_FIELD]
        )
                ? absint(
                        $_POST[self::IMAGE_FIELD]
                )
                : 0;

        $meta->save_image(
                $term_id,
                $image
        );
    }


    /**
     * Get variation swatch metadata module.
     *
     * @return VariationSwatchMeta
     */
    private function get_meta_module(): VariationSwatchMeta
    {
        return $this->container->get(
                VariationSwatchMeta::class
        );
    }


    /**
     * Enqueue admin assets.
     *
     * @param string $hook_suffix Current admin page.
     *
     * @return void
     */
    public function enqueue_assets(
            string $hook_suffix
    ): void
    {

        if (
                'edit-tags.php' !== $hook_suffix &&
                'term.php' !== $hook_suffix
        ) {
            return;
        }

        $screen = get_current_screen();

        if (!$screen) {
            return;
        }

        if (
                !in_array(
                        $screen->taxonomy,
                        $this->get_attribute_taxonomies(),
                        true
                )
        ) {
            return;
        }

        /*
         * WordPress Media Library.
         */
        wp_enqueue_media();

        /*
         * WooShop admin variation swatch JavaScript.
         */
        wp_enqueue_style(
                'wooshop-variation-swatches-admin',
                get_template_directory_uri() . '/assets/src/css/admin/variation-swatches.css',
                [],
                '1.0.0'
        );
        wp_enqueue_script(
                'wooshop-variation-swatches-admin',
                get_template_directory_uri() . '/assets/src/js/admin/variation-swatches.js',
                [],
                '1.0.0',
                true
        );
    }


    /**
     * Add swatch column to the attribute term table.
     *
     * @param array $columns Existing columns.
     * @param string $taxonomy Taxonomy name.
     *
     * @return array
     */
    public function add_term_column(
            array  $columns,
            string $taxonomy
    ): array
    {

        if (!$this->is_attribute_taxonomy($taxonomy)) {
            return $columns;
        }

        $new_columns = [];

        foreach ($columns as $key => $label) {

            $new_columns[$key] = $label;

            if ('name' === $key) {
                $new_columns['wooshop_swatch'] =
                        __('Swatch', 'wooshop');
            }
        }

        return $new_columns;
    }

    /**
     * Render swatch term-list column.
     *
     * @param string $content Existing column content.
     * @param string $column_name Column name.
     * @param int $term_id Term ID.
     *
     * @return string
     */
    public function render_term_column(
            string $content,
            string $column_name,
            int    $term_id
    ): string
    {

        if (
                'wooshop_swatch' !== $column_name
        ) {
            return $content;
        }

        $taxonomy = get_term_field(
                'taxonomy',
                $term_id
        );

        if (
                is_wp_error($taxonomy) ||
                !is_string($taxonomy)
        ) {
            return '';
        }

        if (
                !$this->is_attribute_taxonomy($taxonomy)
        ) {
            return '';
        }

        $type = get_term_meta(
                $term_id,
                '_wooshop_swatch_type',
                true
        );

        $color = get_term_meta(
                $term_id,
                '_wooshop_swatch_color',
                true
        );

        $image_id = absint(
                get_term_meta(
                        $term_id,
                        '_wooshop_swatch_image',
                        true
                )
        );

        ob_start();

        $this->render_term_swatch(
                $type,
                $color,
                $image_id
        );

        return ob_get_clean();
    }


    /**
     * Render a term swatch.
     *
     * @param string $type Swatch type.
     * @param string $color Swatch color.
     * @param int $image_id Image attachment ID.
     *
     * @return void
     */
    private function render_term_swatch(
            string $type,
            string $color,
            int    $image_id
    ): void
    {

        $type = sanitize_key($type);

        $classes = [
                'wooshop-admin-term-swatch',
                'wooshop-admin-term-swatch--' . $type,
        ];

        $class = implode(' ', $classes);

        if ('color' === $type) {

            $color = sanitize_hex_color($color);

            if (!$color) {
                $color = '#ffffff';
            }

            printf(
                    '<span class="%1$s" style="--wooshop-swatch-color:%2$s" aria-label="%3$s"></span>',
                    esc_attr($class),
                    esc_attr($color),
                    esc_attr__('Color swatch', 'wooshop')
            );

            return;
        }

        if ('image' === $type) {

            if (!$image_id) {
                printf(
                        '<span class="%1$s is-empty" aria-label="%2$s"></span>',
                        esc_attr($class),
                        esc_attr__('No swatch image', 'wooshop')
                );

                return;
            }

            $image_url = wp_get_attachment_image_url(
                    $image_id,
                    'thumbnail'
            );

            if (!$image_url) {
                printf(
                        '<span class="%1$s is-empty" aria-label="%2$s"></span>',
                        esc_attr($class),
                        esc_attr__('No swatch image', 'wooshop')
                );

                return;
            }

            printf(
                    '<span class="%1$s" style="--wooshop-swatch-image:url(\'%2$s\')" aria-label="%3$s"></span>',
                    esc_attr($class),
                    esc_url($image_url),
                    esc_attr__('Image swatch', 'wooshop')
            );

            return;
        }

        /*
         * Label/default swatch.
         */
        printf(
                '<span class="%1$s" aria-label="%2$s">Aa</span>',
                esc_attr($class),
                esc_attr__('Label swatch', 'wooshop')
        );
    }

    /**
     * Register term-list columns for WooCommerce attributes.
     *
     * @return void
     */
    public function register_term_columns(): void {

        $taxonomies = $this->get_attribute_taxonomies();

        foreach ( $taxonomies as $taxonomy ) {

            add_filter(
                    "manage_{$taxonomy}_custom_column",
                    [ $this, 'render_term_column' ],
                    10,
                    3
            );

            add_filter(
                    "manage_edit-{$taxonomy}_columns",
                    [ $this, 'add_term_column' ],
                    10,
                    2
            );
        }
    }

    /**
     * Determine whether taxonomy is a WooCommerce
     * product attribute taxonomy.
     *
     * @param string $taxonomy Taxonomy name.
     *
     * @return bool
     */
    private function is_attribute_taxonomy(
            string $taxonomy
    ): bool {

        return in_array(
                $taxonomy,
                $this->get_attribute_taxonomies(),
                true
        );
    }
}
