(function ($) {
    'use strict';

    function initBannerWidget(widget) {

        widget.find('.select-banner-image').off('click').on('click', function (e) {

            e.preventDefault();

            var button = $(this);
            var container = button.closest('p');

            var imageIdField = container.find('.banner-image-id');
            var preview = container.next('.banner-preview');

            var frame = wp.media({
                title: 'Select Banner Image',
                button: {
                    text: 'Use Image'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            frame.on('select', function () {

                var attachment = frame.state().get('selection').first().toJSON();

                // Save Attachment ID
                imageIdField.val(attachment.id).trigger('change');

                // Preview
                preview.html(
                    '<img src="' + attachment.url + '" style="max-width:100%;height:auto;">'
                );

            });

            frame.open();

        });

        widget.find('.remove-banner-image').off('click').on('click', function (e) {

            e.preventDefault();

            var button = $(this);
            var container = button.closest('p');

            container.find('.banner-image-id').val('').trigger('change');

            container.next('.banner-preview').empty();

        });

    }

    // Existing widgets
    $('.widget').each(function () {
        initBannerWidget($(this));
    });

    // Newly added/updated widgets
    $(document).on('widget-added widget-updated', function (event, widget) {
        initBannerWidget($(widget));
    });

})(jQuery);