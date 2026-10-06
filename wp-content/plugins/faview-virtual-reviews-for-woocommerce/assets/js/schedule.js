jQuery(document).ready(function ($) {
    'use strict';

    let body = $('body'),
        ajaxData = {action: 'wvr_action', security: wvrParams.security},
        rulesSection = $('#wvr-schedules-section'),
        scheduleRuleCount = 0,
        categoryOptions = '';
    let weekdays = wvrParams.weekday.map((day, i) => `<option value="${i}">${day}</option>`).join('');
    let days = [...Array(31).keys()].map(i => `<option value="${i + 1}">${i + 1}</option>`).join('');
    let repeatTypes = Object.entries(wvrParams.repeatTypes).map(item => `<option value="${item[0]}">${item[1]}</option>`).join('');

    rulesSection.sortable({
        handle: '.wvr-sort-handler',
        axis: "y",
        placeholder: "wvr-sortable-placeholder",
        start: function (e, ui) {
            ui.placeholder.height(ui.helper[0].scrollHeight);
        }
    });

    for (let id in wvrParams.categories) {
        categoryOptions += `<option value="${id}">${wvrParams.categories[id]}</option>`;
    }

    const ruleTmpl = (args) => {
        let {
            rule_name = 'New rule',
            products = [],
            exclude_products = [],
            quantity = 5,
            quantity_from = 1,
            quantity_to = 3,
            product_limit_from = '',
            product_limit_to = '',
            active = '',
            accordionActive = false,
            date_from = '',
            date_to = '',
            time_from = '12:00 AM',
            time_to = '11:59 PM',
        } = args;

        if (typeof args.quantity_to === 'undefined') quantity_to = quantity;

        let inclProducts = '', exclProducts = '';

        accordionActive = accordionActive ? 'active' : '';
        active = active ? 'checked' : '';

        if (products.length) {
            for (let id of products) {
                if (wvrParams.productList[id]) inclProducts += `<option value="${id}" selected>${wvrParams.productList[id]}</option>`
            }
        }

        if (exclude_products.length) {
            for (let id of exclude_products) {
                if (wvrParams.productList[id]) exclProducts += `<option value="${id}" selected>${wvrParams.productList[id]}</option>`
            }
        }

        let tmpl = $(`<div class="vi-ui styled fluid accordion wvr-schedule-rule-row">
    <div class="${accordionActive} title">
        <i class="dropdown icon"></i>
        <div class="vi-ui toggle checkbox">
            <input type="checkbox" name="wvr_schedule_rules[${scheduleRuleCount}][active]" value="1" ${active}>
            <label></label>
        </div>
        <div class="wvr-schedule-rule-title-group">
            <input type="text" name="wvr_schedule_rules[${scheduleRuleCount}][rule_name]" value="${rule_name}" class="wvr-schedule-rule-title fluid"/>
        </div>
        <i class="arrows alternate icon wvr-sort-handler"> </i>
        <i class="x icon wvr-remove-rule"> </i>
    </div>
    <div class="${accordionActive} content">
        <div class="vi-ui form small">
            <div class="fields">
                <p class="three wide field">Categories</p>
                <div class="thirteen wide field">
                    <select multiple name="wvr_schedule_rules[${scheduleRuleCount}][categories][]" class="vi-ui dropdown fluid wvr-schedule-rule-include-categories">
                        ${categoryOptions}
                    </select>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Exclude Categories</p>
                <div class="thirteen wide field">
                    <select multiple name="wvr_schedule_rules[${scheduleRuleCount}][exclude_categories][]" class="vi-ui dropdown fluid wvr-schedule-rule-exclude-categories">
                        ${categoryOptions}
                    </select>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Products</p>
                <div class="thirteen wide field">
                    <select multiple name="wvr_schedule_rules[${scheduleRuleCount}][products][]" class="wvr-product-search">
                        ${inclProducts}
                    </select>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Exclude Products</p>
                <div class="thirteen wide field">
                    <select multiple name="wvr_schedule_rules[${scheduleRuleCount}][exclude_products][]" class="wvr-product-search">
                        ${exclProducts}
                    </select>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Review quantity / product / day </p>
                <div class="thirteen wide field">
                    <div class="fields">
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">From</span>
                            <input type="number" min="1" step="1" name="wvr_schedule_rules[${scheduleRuleCount}][quantity_from]" value="${quantity_from}">
                        </div>
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">To</span>
                            <input type="number" min="1" step="1" name="wvr_schedule_rules[${scheduleRuleCount}][quantity_to]" value="${quantity_to}">
                        </div>
                    </div>
                    <p>Random once per day, all products in this rule will be use this randomed quantity.</p>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Product limit each add review</p>
                <div class="thirteen wide field">
                    <div class="fields">
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">From</span>
                            <input type="number" min="0" step="1" name="wvr_schedule_rules[${scheduleRuleCount}][product_limit_from]" value="${product_limit_from}">

                        </div>
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">To</span>
                            <input type="number" min="0" step="1" name="wvr_schedule_rules[${scheduleRuleCount}][product_limit_to]" value="${product_limit_to}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="fields">
                <p class="three wide field">Date</p>
                <div class="thirteen wide field">
                    <div class="fields">
                        <div class="four wide field">
                            <select name="wvr_schedule_rules[${scheduleRuleCount}][repeat_type]" class="vi-ui dropdown wvr-repeat-type">
                                ${repeatTypes}
                            </select>
                        </div>
                        <div class="twelve wide field">
                            <div class="wvr-day-options">
                                <hr/>
                            </div>
                        </div>
                    </div>

                    <label>Time in a day</label>
                    <div class="fields">
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">From</span>
                            <div class="vi-ui calendar wvr-time-from">
                                <div class="vi-ui input left icon">
                                    <i class="calendar icon"> </i>
                                    <input type="text" name="wvr_schedule_rules[${scheduleRuleCount}][time_from]" value="${time_from}" required>
                                </div>
                            </div>
                        </div>
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">To</span>
                            <div class="vi-ui calendar wvr-time-to">
                                <div class="vi-ui input left icon">
                                    <i class="calendar icon"> </i>
                                    <input type="text" name="wvr_schedule_rules[${scheduleRuleCount}][time_to]" value="${time_to}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <label>Date range</label>
                    <div class="fields">
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">From</span>
                            <div class="vi-ui calendar wvr-date-from">
                                <div class="vi-ui input left icon">
                                    <i class="calendar icon"> </i>
                                    <input type="text" name="wvr_schedule_rules[${scheduleRuleCount}][date_from]" value="${date_from}">
                                </div>
                            </div>
                        </div>
                        <div class="eight wide field">
                            <span class="wvr-date-range-label">To</span>
                            <div class="vi-ui calendar wvr-date-to">
                                <div class="vi-ui input left icon">
                                    <i class="calendar icon"> </i>
                                    <input type="text" name="wvr_schedule_rules[${scheduleRuleCount}][date_to]" value="${date_to}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>`);

        tmpl.vi_accordion({selector: {trigger: '.dropdown.icon'}});
        tmpl.find('.wvr-schedule-rule-include-categories').viDropdown({placeholder: 'All categories'});
        tmpl.find('.wvr-schedule-rule-exclude-categories').viDropdown({placeholder: 'No category'});

        let currentIndex = scheduleRuleCount;
        tmpl.find('.wvr-repeat-type').viDropdown({
            onChange(value) {
                tmpl.find('.wvr-date-from input').attr('required', false);
                tmpl.find('.wvr-date-to input').attr('required', false);

                switch (value) {
                    case 'none':
                        tmpl.find('.wvr-day-options').html('<hr/>');
                        tmpl.find('.wvr-date-from input').attr('required', true);
                        tmpl.find('.wvr-date-to input').attr('required', true);
                        break;

                    case 'daily':
                        tmpl.find('.wvr-day-options').html('<hr/>');
                        break;

                    case 'weekly':
                        const weeklyOptions = $(`<select name="wvr_schedule_rules[${currentIndex}][repeat_weekday][]" class="fluid wvr-repeat-weekday" multiple>${weekdays}</select>`);
                        tmpl.find('.wvr-day-options').html(weeklyOptions.viDropdown());
                        break;

                    case 'monthly':
                        const monthlyOptions = $(`<select name="wvr_schedule_rules[${currentIndex}][repeat_day][]" class="fluid wvr-repeat-day" multiple>${days}</select>`);
                        tmpl.find('.wvr-day-options').html(monthlyOptions.viDropdown());
                        break;
                }
            }
        });

        let dateStartInput = tmpl.find('.wvr-date-from'),
            dateEndInput = tmpl.find('.wvr-date-to');
        dateStartInput.viCalendar({type: 'date', endCalendar: dateEndInput});
        dateEndInput.viCalendar({type: 'date', startCalendar: dateStartInput});

        let timeStartInput = tmpl.find('.wvr-time-from'),
            timeEndInput = tmpl.find('.wvr-time-to');
        timeStartInput.viCalendar({type: 'time', endCalendar: timeEndInput});
        timeEndInput.viCalendar({type: 'time', startCalendar: timeStartInput});

        tmpl.find('.wvr-product-search').select2({
            width: '100%',
            multiple: true,
            placeholder: 'Search products',
            ajax: {
                url: wvrParams.ajaxUrl,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                delay: 250,
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
        tmpl.find('.select2-search__field').css('width', '100%');

        scheduleRuleCount++;

        return tmpl;
    };

    body
        .on('click', '.wvr-remove-rule', function () {
            $(this).closest('.wvr-schedule-rule-row').remove();
        })
        .on('click', '.wvr-remove-schedule', function () {
            if (!confirm('Do you want to remove this schedule?')) return;

            let $this = $(this),
                timestamp = $(this).data('timestamp'),
                key = $(this).data('key'),
                row = $(this).closest('.wvr-schedule-row');

            if ($this.hasClass('circle notch')) return;

            $.ajax({
                url: wvrParams.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    ...ajaxData,
                    sub_action: 'remove_single_schedule',
                    timestamp,
                    key
                },
                beforeSend() {
                    $this.addClass('circle notch')
                },
                success(res) {
                    if (res.success) {
                        row.remove();
                    }
                    $this.removeClass('circle notch')
                }
            });
        })
        .on('click', '.wvr-remove-all-schedule', function () {
            if (!confirm('Do you want to remove all schedules?')) return;

            let $this = $(this), icon = $this.find('i.icon');
            $.ajax({
                url: wvrParams.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {...ajaxData, sub_action: 'remove_all_schedule'},
                beforeSend() {
                    icon.addClass('circle notch')
                },
                success(res) {
                    if (res.success) {
                        $('.wvr-running-schedules').empty();
                    }
                    icon.removeClass('circle notch')
                }
            });
        });

    $('.wvr-add-schedule-rule').on('click', function () {
        let row = ruleTmpl({accordionActive: true});
        rulesSection.append(row)
    });

    if (wvrParams.schedules.length) {
        let i = 0;
        for (let rule of wvrParams.schedules) {
            if (i++ === 0) rule.accordionActive = true;
            let tmpl = ruleTmpl(rule);
            rulesSection.append(tmpl);
            tmpl.find('.wvr-schedule-rule-include-categories').viDropdown('set selected', rule.categories);
            tmpl.find('.wvr-schedule-rule-exclude-categories').viDropdown('set selected', rule.exclude_categories);
            tmpl.find('.wvr-repeat-type').viDropdown('set selected', rule.repeat_type).trigger('change');
            tmpl.find('.wvr-repeat-weekday').viDropdown('set selected', rule.repeat_weekday);
            tmpl.find('.wvr-repeat-day').viDropdown('set selected', rule.repeat_day);
        }
    } else {
        $('.wvr-add-schedule-rule').trigger('click');
    }
});