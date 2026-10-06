jQuery(document).ready(function ($) {
    'use strict';
    let ajaxData = {action: 'wvr_action', security: wvrParams.security},
        ratingTotal = 0,
        reviewRuleCount = 0,
        ruleSection = $('.wvr-review-rules-section');

    ruleSection.sortable({
        handle: '.wvr-sort-handler',
        axis: "y",
        placeholder: "wvr-sortable-placeholder",
        start: function (e, ui) {
            ui.placeholder.height(ui.helper[0].scrollHeight);
        }
    });

    $('.wvr-dropdown').each(function () {
        let placeholder = $(this).attr('placeholder');
        $(this).viDropdown({placeholder});
    });

    $('.vi-ui.menu .item').viTab({history: true});

    $('.wvr-color-picker').colorPicker();

    $('.wvr-rating-rate').on('change', function () {
        ratingTotal = 0;

        $('.wvr-rating-rate').each(function (i, input) {
            let val = $(input).val();
            ratingTotal += +val;
        });

        let color = ratingTotal === 100 ? 'green' : 'red',
            outline = ratingTotal === 100 ? 'unset' : '1px solid red';

        $('.wvr-rating-rate-total').css({color: color, outline: outline});
        $('.wvr-rating-rate-total-value').text(ratingTotal);

    }).trigger('change');

    $('#wvr-settings-form').on('submit', () => (ratingTotal === 100));

    $('.villatheme-get-key-button').one('click', function (e) {
        let v_button = $(this);
        v_button.addClass('loading');
        let data = v_button.data();
        let item_id = data.id;
        let app_url = data.href;
        let main_domain = window.location.hostname;
        main_domain = main_domain.toLowerCase();
        let popup_frame;
        e.preventDefault();
        let download_url = v_button.attr('data-download');
        popup_frame = window.open(app_url, "myWindow", "width=380,height=600");
        window.addEventListener('message', function (event) {
            /*Callback when data send from child popup*/
            let obj = $.parseJSON(event.data);
            let update_key = '';
            let message = obj.message;
            let support_until = '';
            let check_key = '';
            if (obj['data'].length > 0) {
                for (let i = 0; i < obj['data'].length; i++) {
                    if (obj['data'][i].id === item_id && (obj['data'][i].domain === main_domain || obj['data'][i].domain === '' || obj['data'][i].domain == null)) {
                        if (update_key == '') {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        } else if (support_until < obj['data'][i].support_until) {
                            update_key = obj['data'][i].download_key;
                            support_until = obj['data'][i].support_until;
                        }
                        if (obj['data'][i].domain === main_domain) {
                            update_key = obj['data'][i].download_key;
                            break;
                        }
                    }
                }
                if (update_key) {
                    check_key = 1;
                    $('.villatheme-autoupdate-key-field').val(update_key);
                }
            }
            v_button.removeClass('loading');
            if (check_key) {
                $('<p><strong>' + message + '</strong></p>').insertAfter(".villatheme-autoupdate-key-field");
                $(v_button).closest('form').submit();
            } else {
                $('<p><strong> Your key is not found. Please contact support@villatheme.com </strong></p>').insertAfter(".villatheme-autoupdate-key-field");
            }
        });
    });

    /*=BODY CLICK================================================================================================*/
    const getTransUrl = (lang, text) => `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en-US&tl=${lang}&hl=en-US&dt=t&dt=bd&dj=1&source=input&q=${text}`;

    $('body')
        .on('click', '.wvr-remove-lang-row', function () {
            $(this).closest('.wvr-lang-textarea-row').remove();
        })
        .on('click', '.wvr-remove-language', function () {
            $(this).closest('.wvr-language-row').remove();
        })
        .on('click', '.wvr-remove-rule', function () {
            $(this).closest('.wvr-review-rule-row').remove();
        })
        .on('click', '.wvr-translate', function () {
            let targetLang = $(this).data('lang');
            let thisSection = $(this).closest('.wvr-translatable-section');

            for (let i = 1; i <= 5; i++) {
                let sourceContent = thisSection.find(`[data-source=default_${i}]`);
                let contentText = sourceContent.text();
                let textArray = contentText.split(/\r?\n/).filter(element => element);

                textArray = textArray.map((value, index) => {
                    return new Promise((resolve, reject) => {
                        let url = getTransUrl(targetLang, value);
                        fetch(url).then(res => res.json()).then(res => {
                            let transed = res.sentences[0].trans;
                            resolve(transed)
                        }).catch(res => reject(res))
                    })
                });

                Promise.all(textArray).then(res => {
                    thisSection.find(`[data-source=${targetLang}_${i}]`).val(res.join("\n"));
                }).catch(res => {
                    console.log(res)
                });
            }
        });

    /*=================================================================================================*/

    //Assign comment rules
    {
        let categoryOptions = '';
        for (let id in wvrParams.categories) {
            categoryOptions += `<option value="${id}">${wvrParams.categories[id]}</option>`;
        }

        let langOptions = '';
        for (let id in wvrParams.languages) {
            langOptions += `<option value="${id}">${wvrParams.languages[id]}</option>`;
        }

        const reviewContentTmpl = (index, title = '', lang = 'default', comments = []) => {
            let removeBtn = lang !== 'default' ? `<i class="dashicons dashicons-translation wvr-translate" data-lang="${lang}"> </i><i class="x icon wvr-remove-language"> </i>` : '';
            let classHidden = wvrParams.languages.length === 0 && lang !== 'default' ? 'wvr-hidden' : '';

            const textArea = (rating) => {
                let cmt = comments[rating] && Array.isArray(comments[rating]) ? comments[rating].join("\n") : '';

                return `<div class="field">
                        <p>Review for ${rating} &#9733;</p>
                        <textarea name="wvr_review_rules[${index}][comments][${lang}][${rating}]" data-source="${lang}_${rating}" >${cmt}</textarea>
                    </div>`
            };

            return `<div class="accordion wvr-language-row ${classHidden}">
                    <div class="active title">
                        <i class="dropdown icon"></i>
                        <span class="wvr-language-title">${title}</span>
                        ${removeBtn}
                    </div>
                    <div class="active content">
                        ${textArea(5)}
                        ${textArea(4)}
                        <div class="three fields">
                              ${textArea(3)}
                              ${textArea(2)}
                              ${textArea(1)}
                        </div>
                    </div>
                </div>`;
        };

        const reviewRuleTmpl = ({rule_name = 'New rule', rule_id = '', products = [], exclude_products = [], comments = [], active = false}) => {
            let inclProducts = '', exclProducts = '';
            active = active ? 'active' : '';
            rule_id = rule_id ? rule_id : Date.now();

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

            let reviewList = '';
            if (Object.keys(comments).length) {
                for (let lang in comments) {
                    let title = wvrParams.languages[lang] || '';

                    if (Object.keys(wvrParams.languages).length && !title) title = 'Default language';

                    reviewList += reviewContentTmpl(reviewRuleCount, title, lang, comments[lang]);
                }
            } else {
                reviewList = reviewContentTmpl(reviewRuleCount);
            }

            let langSelect = langOptions ? `<div class="vi-ui action input">
                                <select class="vi-ui compact search dropdown fluid wvr-language-list">
                                    <option value="">Add review in other language</option>
                                    ${langOptions}
                                </select>
                                <div class="vi-ui button icon wvr-add-review-content-language" data-index="${reviewRuleCount}"><i class="icon plus"> </i></div>
                            </div>` : '';

            let tmpl = $(`<div class="vi-ui styled fluid accordion wvr-review-rule-row">
                        <div class="${active} title">
                            <i class="dropdown icon"></i>
                            <div class="wvr-review-rule-title-group">
                                <input type="hidden" name="wvr_review_rules[${reviewRuleCount}][rule_id]" value="${rule_id}" class="wvr-review-rule-title"/>
                                <input type="text" name="wvr_review_rules[${reviewRuleCount}][rule_name]" value="${rule_name}" class="wvr-review-rule-title"/>
                            </div>
                            <i class="arrows alternate icon wvr-sort-handler"> </i>
                            <i class="x icon wvr-remove-rule"> </i>
                        </div>
                        <div class="${active} content">
                            <h4 class="vi-ui header">Condition</h4>
                            <div class="vi-ui form small">
                                <div class="fields">
                                    <p class="three wide field">Categories</p>
                                    <div class="thirteen wide field">
                                        <select multiple name="wvr_review_rules[${reviewRuleCount}][categories][]" class="vi-ui dropdown fluid wvr-review-rule-include-categories"> 
                                            ${categoryOptions}
                                        </select>
                                    </div>
                                </div>
                                <div class="fields">
                                    <p class="three wide field">Exclude Categories</p>
                                    <div class="thirteen wide field">
                                        <select multiple name="wvr_review_rules[${reviewRuleCount}][exclude_categories][]" class="vi-ui dropdown fluid wvr-review-rule-exclude-categories"> 
                                            ${categoryOptions}
                                        </select>
                                    </div>
                                </div>
                                <div class="fields">
                                    <p class="three wide field">Products</p>
                                    <div class="thirteen wide field">
                                        <select multiple name="wvr_review_rules[${reviewRuleCount}][products][]" class="wvr-product-search">
                                            ${inclProducts}
                                        </select>
                                    </div>
                                </div>
                                <div class="fields">
                                    <p class="three wide field">Exclude Products</p>
                                    <div class="thirteen wide field">
                                        <select multiple name="wvr_review_rules[${reviewRuleCount}][exclude_products][]" class="wvr-product-search"> 
                                            ${exclProducts}
                                        </select>
                                    </div>
                                </div>
                            </div>
            
                            <h4 class="vi-ui header">Review content</h4>
                            <div class="wvr-review-content-section wvr-translatable-section">
                                ${reviewList}
                            </div>
                            ${langSelect}
                        </div>
                    </div>`);

            tmpl.vi_accordion({selector: {trigger: '.dropdown.icon'}});
            tmpl.find('.wvr-review-rule-include-categories').viDropdown({placeholder: 'All categories'});
            tmpl.find('.wvr-review-rule-exclude-categories').viDropdown({placeholder: 'No category'});

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

            let langList = tmpl.find('.wvr-language-list').viDropdown({clearable: true});

            tmpl.on('click', '.wvr-add-review-content-language', function () {
                let lang = langList.viDropdown('get value'),
                    text = langList.viDropdown('get text'),
                    index = $(this).attr('data-index');

                if (lang) {
                    let reviewContentList = reviewContentTmpl(index, text, lang);
                    tmpl.find('.wvr-review-content-section').append(reviewContentList);
                    langList.viDropdown('clear')
                }
            });

            reviewRuleCount++;

            return tmpl;
        };

        $('.wvr-add-review-rule').on('click', function () {
            ruleSection.append(reviewRuleTmpl({active: true}));
        });

        if (wvrParams.settings.review_rules.length) {
            let i = 0;
            for (let rule of wvrParams.settings.review_rules) {
                if (i++ === 0) rule.active = true;
                let tmpl = reviewRuleTmpl(rule);
                ruleSection.append(tmpl);
                tmpl.find('.wvr-review-rule-include-categories').viDropdown('set selected', rule.categories);
                tmpl.find('.wvr-review-rule-exclude-categories').viDropdown('set selected', rule.exclude_categories);
            }
        } else {
            $('.wvr-add-review-rule').trigger('click');
        }
    }

    //Author names
    {
        let nameLangList = $('.wvr-names-language-list');
        nameLangList.viDropdown({clearable: true});

        const nameAuthorTmpl = ({value = [], text = '', lang = ''}) => {
            let removeBtn = lang !== 'default' ? `<span class="wvr-remove-lang-row"><i class="icon x"> </i></span>` : '';
            let classHidden = wvrParams.languages.length === 0 && lang !== 'default' ? 'wvr-hidden' : '';
            text = text ? `Names in ${text}` : '';
            if (Array.isArray(value)) value = value.join("\n");

            return `<div class="wvr-names-row wvr-lang-textarea-row ${classHidden}">
                    <p class="wvr-names-row-label">${text}</p>
                    <textarea name="wvr_names[${lang}]" required>${value}</textarea>
                    ${removeBtn}
                </div>`;
        };

        let nameArray = wvrParams.settings.names || {};

        if (typeof nameArray === 'undefined' || Object.keys(nameArray).length === 0) {
            nameArray = {default: []};
        }

        for (let lang in nameArray) {
            $('.wvr-names-field').append(nameAuthorTmpl({text: wvrParams.languages[lang], lang, value: nameArray[lang]}));
        }

        $('.wvr-add-language-author').on('click', function () {
            let lang = nameLangList.viDropdown('get value'),
                text = nameLangList.viDropdown('get text');
            let textArea = nameAuthorTmpl({text, lang});
            if (lang) $('.wvr-names-field').append(textArea);

            nameLangList.viDropdown('clear')
        });
    }

    // Reply settings
    {
        const replyRow = ({lang = '', text = '', replys = {}}) => {
            let classHidden = wvrParams.languages.length === 0 && lang !== 'default' ? 'wvr-hidden' : '';
            let removeBtn = lang !== 'default' ? `<i class="dashicons dashicons-translation wvr-translate" data-lang="${lang}"> </i><i class="x icon wvr-remove-language"> </i>` : '';

            const textArea = (rating) => {
                let cmt = replys[rating] || '';
                if (Array.isArray(cmt)) cmt = cmt.join("\n");

                return `<div class="field">
                        <p>Reply for ${rating} &#9733;</p>
                        <textarea name="wvr_reply_content[${lang}][${rating}]" data-source="${lang}_${rating}">${cmt}</textarea>
                    </div>`
            };

            let tmpl = $(`<div class="vi-ui styled fluid accordion wvr-language-row ${classHidden}">
                    <div class="active title">
                        <i class="dropdown icon"> </i>
                       <span class="wvr-language-title">${text}</span>
                       ${removeBtn}
                    </div>
                    <div class="active content">
                        ${textArea(5)}
                        ${textArea(4)}
                        ${textArea(3)}
                        ${textArea(2)}
                        ${textArea(1)}
                    </div>
                </div>`);

            tmpl.vi_accordion({selector: {trigger: '.dropdown.icon'}});

            return tmpl;
        };

        let replysLangList = $('.wvr-replys-language-list');
        replysLangList.viDropdown({clearable: true});

        let replyContent = wvrParams.settings.reply_content || {};

        if (typeof replyContent === 'undefined' || Object.keys(replyContent).length === 0) {
            replyContent = {default: {1: '', 2: '', 3: '', 4: '', 5: ''}};
        }

        for (let lang in replyContent) {
            let text = wvrParams.languages[lang] || '';
            if (Object.keys(wvrParams.languages).length && !text) text = 'Default language';
            $('.wvr-reply-content-section').append(replyRow({lang, text, replys: replyContent[lang]}));
        }

        $('.wvr-add-language-reply').on('click', function () {
            let lang = replysLangList.viDropdown('get value'),
                text = replysLangList.viDropdown('get text');
            let textArea = replyRow({text, lang});
            if (lang) $('.wvr-reply-content-section').append(textArea);

            replysLangList.viDropdown('clear')
        });

        $('.wvr-reply-author').select2({
            width: '100%',
            placeholder: 'Search user',
            ajax: {
                url: wvrParams.ajaxUrl,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                delay: 250,
                data: params => ({
                    ...ajaxData,
                    sub_action: 'search_user',
                    keyword: params.term
                }),
                processResults: data => ({results: data}),
                cache: true
            },
            escapeMarkup: markup => markup,
            minimumInputLength: 2
        });
    }


    // Review form settings
    {
        const cmtFrontendRow = ({value = [], text = '', lang = ''}) => {
            let removeBtn = lang !== 'default' ? `<span class="wvr-remove-lang-row"><i class="icon x"> </i></span>` : '';
            if (Array.isArray(value)) value = value.join("\n");
            text = text ? `${text}` : '';
            let classHidden = wvrParams.languages.length === 0 && lang !== 'default' ? 'wvr-hidden' : '';

            return `<div class="wvr-cmt-frontend-row wvr-lang-textarea-row ${classHidden}">
                    <p class="wvr-names-row-label">${text}</p>
                    <textarea name="wvr_cmt_frontend[${lang}]">${value}</textarea>
                    ${removeBtn}
                </div>`;
        };

        let cmtLangList = $('.wvr-cmt-frontend-language-list');
        cmtLangList.viDropdown({clearable: true});

        let cmtFrontend = wvrParams.settings.cmt_frontend || {};
        if (typeof cmtFrontend !== 'object' || Object.keys(cmtFrontend).length === 0 || !cmtFrontend.default) {
            cmtFrontend = {default: ''};
        }

        for (let lang in cmtFrontend) {
            let text = wvrParams.languages[lang] || '';
            $('.wvr-cmt-frontend-section').append(cmtFrontendRow({lang, text, value: cmtFrontend[lang]}));
        }

        $('.wvr-add-language-cmt').on('click', function () {
            let lang = cmtLangList.viDropdown('get value'),
                text = cmtLangList.viDropdown('get text');
            let textArea = cmtFrontendRow({text, lang});
            if (lang) $('.wvr-cmt-frontend-section').append(textArea);

            cmtLangList.viDropdown('clear')
        });

    }

});