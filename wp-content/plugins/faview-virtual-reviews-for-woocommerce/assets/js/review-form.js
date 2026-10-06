jQuery(document).ready(function ($) {
    'use strict';
    let commentTextarea = $('textarea#comment');

    $('.wvr-customer-select').on('change', function () {
        let value = $(this).val();
        commentTextarea.focus();
        document.execCommand('insertText', false, `${value} `);
    });

    $('.wvr-select-sample-cmt').on('click', function () {
        let value = $(this).text();
        commentTextarea.focus();
        document.execCommand('insertText', false, `${value} `);
    });

    if (wvrParams.auto_rating) {
        setTimeout(() => {
            $('.comment-form-rating a.star-5').trigger('click');
        }, 1000);

        $('select#rating option[value="5"]').attr('selected', 'selected');
    }

    if (wvrParams.first_comment) {
        commentTextarea.val(`${wvrParams.first_comment} `);
    }
});




