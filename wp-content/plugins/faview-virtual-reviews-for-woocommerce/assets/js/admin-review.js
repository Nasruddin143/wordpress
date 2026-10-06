jQuery(document).ready(function ($) {
    'use strict';

    let ajaxData = {action: 'wvr_action', security: wvrParams.security};

    $('.wvr-products-search').each(function () {
        let placeholder = $(this).attr('placeholder');
        $(this).select2({
            width: '100%',
            multiple: true,
            placeholder: placeholder,
            ajax: {
                url: wvrParams.ajaxUrl,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                delay: 500,
                data: params => ({
                    ...ajaxData,
                    sub_action: 'search_product',
                    keyword: params.term
                }),
                processResults: data => ({results: data}),
                cache: true
            },
            escapeMarkup: markup => markup,
            minimumInputLength: 2
        });
    });

    const MultipleReviews = {
        init() {
            let dateStartInput = $('#wvr-date-start'),
                dateEndInput = $('#wvr-date-end');

            dateStartInput.viCalendar({type: 'date', endCalendar: dateEndInput});
            dateEndInput.viCalendar({type: 'date', startCalendar: dateStartInput});

            $('.wvr-include-product-cat').viDropdown({placeholder: 'All categories', fullTextSearch: true});
            $('.wvr-exclude-product-cat').viDropdown({placeholder: 'No category', fullTextSearch: true});

            this.processing = false;
            this.form = $('#wvr-review-from-setting');
            this.progressbar = $('#wvr-processing-bar');
            this.qtyNotice = $(`<span>Quantity is invalid</span>`);
            this.form.on('click', '.wvr-add-multi-reviews', () => this.addMultiReview());

            $('.wvr-use-quantity-range').on('change', this.useQuantityRange);
        },

        addMultiReview() {
            let useRandom = $('.wvr-use-quantity-range').is(':checked');
            let fromQuantity = null, toQuantity = null, qty = null;

            if (useRandom) {
                let fromQuantityField = $('.wvr-review-per-product-from');
                let toQuantityField = $('.wvr-review-per-product-to');
                fromQuantity = +fromQuantityField.val();
                toQuantity = +toQuantityField.val();

                if (fromQuantity < 1 || fromQuantity > 50) {
                    fromQuantityField.after(this.qtyNotice);
                    return;
                }

                if (toQuantity < 1 || toQuantity > 50) {
                    toQuantityField.after(this.qtyNotice);
                    return;
                }
            } else {
                let qtyInput = this.form.find('.wvr-review-per-product');
                qty = +qtyInput.val();

                if (qty < 1) {
                    qtyInput.after(this.qtyNotice);
                    return;
                }
            }

            if (this.processing) return;

            this.progressbar.hide();
            $('.wvr-error-product-list-wrapper').hide();
            $('.wvr-error-products').html('');

            if (!confirm('Do you want to add reviews?')) return;

            this.processing = true;
            this.progressbar.viProgress({percent: 0});
            this.progressbar.show();

            let data = {
                sub_action: 'add_multiple_reviews',
                use_random_quantity: useRandom,
                qty: qty,
                from_qty: fromQuantity,
                to_qty: toQuantity,
                include_cats: this.form.find('.wvr-include-product-cat').viDropdown('get value'),
                exclude_cats: this.form.find('.wvr-exclude-product-cat').viDropdown('get value'),
                include_products: this.form.find('.wvr-include-products').val(),
                exclude_products: this.form.find('.wvr-exclude-products').val(),
                from: this.form.find('.wvr-date-from').val(),
                to: this.form.find('.wvr-date-to').val(),
                step: 1
            };

            this.ajaxAddReview(data);
        },

        ajaxAddReview(data) {
            let originData = data;
            let $this = this;
            $.ajax({
                url: wvrParams.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {...ajaxData, ...originData},
                success(res) {
                    console.log(res)
                    if (res.success) {
                        let {percent, error_products = {}, step, next_lang, paged} = res.data;
                        $this.progressbar.viProgress({percent: +percent});
                        if (step) {
                            data = {...originData, ...res.data};
                            $this.ajaxAddReview(data);
                        } else if (next_lang) {
                            setTimeout(() => $this.progressbar.viProgress({percent: 0}), 500);
                            step = 1;
                            paged = 1;
                            data = {...originData, ...res.data, step, paged};
                            $this.ajaxAddReview(data);
                        } else {
                            $this.processing = false;
                        }

                        if (Object.keys(error_products).length) {
                            $('.wvr-error-product-list-wrapper').show();
                            let frame = $('.wvr-error-products');
                            for (let id in error_products) {
                                frame.append(`<li>${error_products[id]}</li>`);
                            }
                        }
                    } else {
                        // $this.ajaxAddReview(data);
                    }
                }
            });
        },

        useQuantityRange() {
            let stt = $(this).is(':checked');

            if (stt) {
                $('.wvr-random-quantity-field').show();
                $('.wvr-fixed-quantity-field').hide();
            } else {
                $('.wvr-fixed-quantity-field').show();
                $('.wvr-random-quantity-field').hide();
            }

            $.ajax({
                url: wvrParams.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {...ajaxData, stt, sub_action: 'use_quantity_range'},
                success(res) {
                }
            });
        }
    };

    const SingleReview = {
        init() {
            this.form = $('#wvr-custom-review');
            this.progressbar = this.form.find('.wvr-processing-bar');
            this.form.on('click', '.wvr-add-review', (e) => this.addReview(e));
            $('#wvr-single-picker-time').viCalendar({ampm: false});
            $('.wvr-rating').viDropdown();
        },

        addReview(e) {
            let btn = $(e.target);
            if (btn.hasClass('loading')) {
                return;
            }

            let form = this.form, error = 0;

            ['.wvr-products', '.wvr-review', '.wvr-author'].forEach(function (el) {
                let input = form.find(el);
                input.removeClass('wvr-error');
                if (input.hasClass('select2-hidden-accessible')) input.next().removeClass('wvr-error');

                let value = input.val();

                if (!value || value.length === 0) {
                    error++;
                    input.addClass('wvr-error');
                    if (input.hasClass('select2-hidden-accessible')) input.next().addClass('wvr-error');
                }
            });

            if (error) return;

            let data = {
                sub_action: 'add_custom_reviews',
                pids: form.find('.wvr-products').val(),
                time: form.find('.wvr-time').val(),
                cmt: form.find('.wvr-review').val(),
                author: form.find('.wvr-author').val(),
                rating: form.find('.wvr-rating').viDropdown('get value')
            };

            $.ajax({
                url: wvrParams.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {...ajaxData, ...data},
                beforeSend() {
                    btn.addClass('loading')
                },
                success(res) {
                    btn.removeClass('loading');
                },
                error(res) {
                }
            });
        },
    };

    MultipleReviews.init();
    SingleReview.init();

});