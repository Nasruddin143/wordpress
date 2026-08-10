/**
 * WooShop Variation Swatch Admin
 *
 * Handles variation swatch administration.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const typeField = document.getElementById(
        'wooshop_swatch_type'
    );

    const colorInput = document.getElementById(
        'wooshop_swatch_color'
    );

    const imageInput = document.getElementById(
        'wooshop_swatch_image'
    );

    const colorField = document.querySelector(
        '.wooshop-swatch-color-field'
    );

    const imageField = document.querySelector(
        '.wooshop-swatch-image-field'
    );

    const imagePreview = document.querySelector(
        '.wooshop-swatch-image-preview'
    );

    const swatchPreview = document.querySelector(
        '.wooshop-swatch-preview'
    );

    const swatchPreviewLabel =
        document.querySelector(
            '.wooshop-swatch-preview-label'
        );

    const uploadButton = document.querySelector(
        '.wooshop-upload-swatch-image'
    );

    const removeButton = document.querySelector(
        '.wooshop-remove-swatch-image'
    );


    /**
     * Get current swatch type.
     *
     * @return {string}
     */
    const getSwatchType = () => {
        return typeField
            ? typeField.value
            : 'label';
    };


    /**
     * Toggle admin fields.
     *
     * @return {void}
     */
    const toggleFields = () => {

        const type = getSwatchType();

        if (colorField) {
            colorField.hidden =
                type !== 'color';
        }

        if (imageField) {
            imageField.hidden =
                type !== 'image';
        }
    };


    /**
     * Update image preview.
     *
     * @param {Object} attachment WordPress attachment.
     *
     * @return {void}
     */
    const updateImagePreview = (attachment) => {

        if (!imageInput || !imagePreview) {
            return;
        }

        const attachmentId =
            attachment?.id || '';

        const imageUrl =
            attachment?.sizes?.thumbnail?.url ||
            attachment?.url ||
            '';

        imageInput.value =
            String(attachmentId);

        imagePreview.replaceChildren();

        if (!imageUrl) {
            return;
        }

        const image =
            document.createElement('img');

        image.src = imageUrl;

        image.alt = '';

        image.loading = 'lazy';

        image.width = 100;

        image.height = 100;

        imagePreview.appendChild(image);
    };


    /**
     * Update live swatch preview.
     *
     * @return {void}
     */
    const updateSwatchPreview = () => {

        if (!swatchPreview) {
            return;
        }

        const type = getSwatchType();

        swatchPreview.dataset.type =
            type;

        /*
         * Label swatch.
         */
        if (type === 'label') {

            swatchPreview.style.backgroundImage =
                '';

            swatchPreview.style.backgroundColor =
                '';

            swatchPreview.classList.remove(
                'has-image',
                'has-color'
            );

            swatchPreview.classList.add(
                'is-label'
            );

            return;
        }


        /*
         * Color swatch.
         */
        if (type === 'color') {

            const color =
                colorInput?.value ||
                '#ffffff';

            swatchPreview.style.backgroundImage =
                '';

            swatchPreview.style.backgroundColor =
                color;

            swatchPreview.classList.remove(
                'is-label',
                'has-image'
            );

            swatchPreview.classList.add(
                'has-color'
            );

            return;
        }


        /*
         * Image swatch.
         */
        if (type === 'image') {

            const previewImage =
                imagePreview?.querySelector(
                    'img'
                );

            swatchPreview.style.backgroundColor =
                '';

            swatchPreview.classList.remove(
                'is-label',
                'has-color'
            );

            swatchPreview.classList.add(
                'has-image'
            );

            if (previewImage) {

                swatchPreview.style.backgroundImage =
                    `url("${previewImage.src}")`;

            } else {

                swatchPreview.style.backgroundImage =
                    '';
            }
        }
    };


    /**
     * Open WordPress Media Library.
     *
     * @return {void}
     */
    const openMediaLibrary = () => {

        if (
            typeof window.wp === 'undefined' ||
            typeof window.wp.media !== 'function'
        ) {
            return;
        }

        const frame =
            window.wp.media({

                title:
                    'Select Swatch Image',

                button: {
                    text:
                        'Use This Image'
                },

                multiple: false,

                library: {
                    type: 'image'
                }
            });


        frame.on(
            'select',
            () => {

                const selection =
                    frame
                        .state()
                        .get('selection');

                const attachment =
                    selection
                        .first()
                        .toJSON();

                updateImagePreview(
                    attachment
                );

                updateSwatchPreview();
            }
        );


        frame.open();
    };


    /**
     * Remove image.
     *
     * @return {void}
     */
    const removeImage = () => {

        if (imageInput) {
            imageInput.value = '';
        }

        if (imagePreview) {
            imagePreview.replaceChildren();
        }

        updateSwatchPreview();
    };


    /**
     * Update preview label.
     *
     * @return {void}
     */
    const updatePreviewLabel = () => {

        if (!swatchPreviewLabel) {
            return;
        }

        const termNameField =
            document.getElementById('name');

        if (
            termNameField &&
            termNameField.value.trim()
        ) {

            swatchPreviewLabel.textContent =
                termNameField.value.trim();

            return;
        }

        swatchPreviewLabel.textContent =
            'Preview';
    };


    /**
     * Events.
     */

    if (typeField) {

        typeField.addEventListener(
            'change',
            () => {

                toggleFields();

                updateSwatchPreview();
            }
        );
    }


    if (colorInput) {

        colorInput.addEventListener(
            'input',
            updateSwatchPreview
        );

        colorInput.addEventListener(
            'change',
            updateSwatchPreview
        );
    }


    if (uploadButton) {

        uploadButton.addEventListener(
            'click',
            (event) => {

                event.preventDefault();

                openMediaLibrary();
            }
        );
    }


    if (removeButton) {

        removeButton.addEventListener(
            'click',
            (event) => {

                event.preventDefault();

                removeImage();
            }
        );
    }


    const termNameField =
        document.getElementById('name');

    if (termNameField) {

        termNameField.addEventListener(
            'input',
            updatePreviewLabel
        );
    }


    /**
     * Initialize.
     */

    toggleFields();

    updatePreviewLabel();

    updateSwatchPreview();
});