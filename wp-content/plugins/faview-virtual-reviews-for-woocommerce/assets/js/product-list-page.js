jQuery(document).ready(function ($) {
    'use strict';

    let ajaxData = {action: 'wvr_action', security: wvrParams.security};
    let processing = false;
    let progressBar = $(`<div class="wvr-add-review-progress-bar" data-percent="75">
                            <div class="wvr-bar">
                                <div class="wvr-progress">0%</div>
                            </div>
                        </div>`);

    const setBarPercent = (percent) => {
        progressBar.find('.wvr-bar').css('width', `${percent}%`);
        progressBar.find('.wvr-progress').text(`${percent}%`);
    };

    const addReviews = (data) => {
        let originData = data;

        $.ajax({
            url: wvrParams.ajaxUrl,
            type: 'post',
            dataType: 'json',
            data: {sub_action: 'add_review_from_product_page', ...ajaxData, ...data},
            success(res) {
                if (res.success) {
                    let {percent, error_products = {}, step} = res.data;
                    setBarPercent(percent);

                    if (step) {
                        data = {...originData, ...res.data};
                        addReviews(data);
                    } else {
                        processing = false;

                        if ($('.wvr-products-error-list').html()) {
                            $('.wvr-products-error-section').show();
                        }

                        setTimeout(() => progressBar.remove(), 2000);
                    }

                    if (Object.keys(error_products).length) {
                        $('.wvr-products-error-button').show();
                        let frame = $('.wvr-products-error-list');
                        for (let id in error_products) {
                            frame.append(`<li>${error_products[id]}</li>`);
                        }
                    }
                }
            },
            error(res) {
                console.log(res)
            }
        });
    };

    $('body')
        .on('click', function (e) {
            if ($(e.target).closest('.wvr-control-panel').length === 0) {
                $('.wvr-add-review-control').removeClass('wvr-open');
                $('.wvr-products-error-section').hide();
            }
        })
        .on('click', '.wvr-products-error-button', function () {
            $('.wvr-products-error-section').toggle();
        });

    $('.wvr-open-add-review-control-panel').on('click', function () {
        $('.wvr-add-review-control').toggleClass('wvr-open');
    });

    $('.submit-add-reviews').on('click', function (e) {
        if (processing) return;

        let selected = $("input:checkbox[id^='cb-select-']:checked");

        if (!selected.length) {
            alert("You have to select at least one product");
            return;
        }

        let quantity = $('.wvr-select-qty-cmt').val();
        let from = $('.wvr-date-from').val();
        let to = $('.wvr-date-to').val();

        if (!(quantity && from && to)) {
            alert("Params are invalid");
            return;
        }

        if (!confirm(`Do you want to add ${quantity} comment(s) per product?`)) return;

        let pids = selected.map((index, input) => input.value).toArray();
        pids = pids.filter(el => el !== 'on');

        $('.wp-list-table').before(progressBar);
        $('.wvr-add-review-control').removeClass('wvr-open');

        processing = true;
        setBarPercent(0);
        $('.wvr-products-error-list').empty();

        addReviews({pids, quantity, from, to});
    });


});