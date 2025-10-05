(function($) {
    'use strict';

    var EasyElementsAdmin = {
        
        init: function() {
            this.initWidgetToggles();
            this.initSearchFunctionality();
            this.initBulkActions();
            this.initAllExtensions();
            this.easyEltab();
            this.easyElFilter();
            this.easyelVideoPopuo();
            this.easyelFaq();
        },

        initWidgetToggles: function() {
            $('.widget-toggle-checkbox').on('change', function() {
                var checkbox = $(this);
                var widgetKey = checkbox.data('widget-key');
                var status = checkbox.is(':checked') ? '1' : '0';
                
                if (!widgetKey) return;
                
                $.post(ajaxurl, {
                    action: 'easy_elements_save_widget_setting',
                    widget_key: widgetKey,
                    status: status,
                    nonce: easyElementsData.widget_settings_nonce
                })
                .done(function(response) {
                    if (!response.success) {
                        checkbox.prop('checked', !checkbox.is(':checked')); // revert checkbox
                    }
                })
                .fail(function() {
                    checkbox.prop('checked', !checkbox.is(':checked')); // revert checkbox
                });
            });
        },

        initSearchFunctionality: function() {
            $('#element-search').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.easy-widget-item').each(function() {
                    var widgetTitle = $(this).find('.widget-header strong').text().toLowerCase();
                    var widgetDesc = $(this).find('.widget-description').text().toLowerCase();
                    
                    if (widgetTitle.includes(searchTerm) || widgetDesc.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        },

        initBulkActions: function() {
            $('#activate-all-btn').on('click', function() {
                var currentTab = $('.easyel-nav-tab-active').data('tab');
                EasyElementsAdmin.performBulkAction('activate_all', currentTab);
            });

            $('#deactivate-all-btn').on('click', function() {
                EasyElementsAdmin.performBulkAction('deactivate_all');
            });
        },

        performBulkAction: function(action) {
            var currentTab = $('.easyel-nav-tab-active').data('tab');
            var btn = action === 'activate_all' ? $('#activate-all-btn') : $('#deactivate-all-btn');
            var originalText = btn.text();

            btn.prop('disabled', true).text(easyElementsData.strings.processing);

            $.post(ajaxurl, {
                action: 'easy_elements_bulk_action',
                bulk_action: action,
                tab: currentTab,
                nonce: easyElementsData.bulk_action_nonce
            })
            .done(function(response) {
                if (response.success) {
                    $('.widget-toggle-checkbox').each(function() {
                        var $checkbox = $(this);

                        if ($checkbox.data('tab') === currentTab) {
                            var isPro = $checkbox.closest('.easy-widget-item').hasClass('easyel-pro-enable');
                            if (isPro && !response.data.is_pro_active) {
                                $checkbox.prop('checked', false).prop('disabled', true);
                            } else {
                                $checkbox.prop('checked', action === 'activate_all');
                                $checkbox.prop('disabled', false);
                            }
                        }
                    });
                }
            })
            .always(function() {
                btn.prop('disabled', false).text(originalText);
            });
        },

        initAllExtensions: function() {
            let $extensionsTab = $('.easyel-tab-panel.extensions');

            $('.easyel-extension-toggle').on('change', function () {
                let checkbox = $(this);
                let key = checkbox.data('key');
                let tab = checkbox.data('tab');
                let status = checkbox.is(':checked') ? 1 : 0;

                $.post(ajaxurl, {
                    action: 'easy_elements_save_global_extensions',
                    tab: tab,
                    key: key,
                    status: status,
                    nonce: easyElementsData.widget_settings_nonce
                })
                .fail(function () {
                    checkbox.prop('checked', !checkbox.is(':checked'));
                });
            });

            $extensionsTab.on('change', '.easyel-group-toggle', function () {
                let groupCheckbox = $(this);
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-extension-wrapper[data-group="' + groupSlug + '"]');
                let hiddenField = $('input.easyel-group-hidden[name="easy_element_group_' + groupSlug + '"]');

                let status = groupCheckbox.is(':checked') ? 1 : 0;
                groupWrapper.find('.easyel-extension-toggle').each(function () {
                    let checkbox = $(this);
                    if (checkbox.closest('.easyel-extension-item').hasClass('easyel-pro-enable')) return;
                    checkbox.prop('checked', status === 1);
                });

                hiddenField.val(status);

                let keys = [];
                groupWrapper.find('.easyel-extension-toggle').each(function() { keys.push($(this).data('key')); });
                if (keys.length) {
                    $.post(ajaxurl, {
                        action: 'easy_elements_save_global_extensions_bulk',
                        tab: 'extensions',
                        keys: keys,
                        status: status,
                        group: groupSlug, 
                        nonce: easyElementsData.widget_settings_nonce
                    });
                }
            });
        },

        easyEltab: function() {
            $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a').on('click', function (e) {
                const href = $(this).attr('href');
                const isDashboardPage = window.location.href.includes('page=easy-elements-dashboard');

                if (isDashboardPage && href.includes('#')) {
                    e.preventDefault();

                    let tab = 'overview';
                    if (href.includes('#widget')) tab = 'widget';
                    if (href.includes('#extensions')) tab = 'extensions';

                    activateTab(tab);
                    history.replaceState(null, null, '#'+tab);
                }
            });

            let hash = window.location.hash.substring(1);
            if (hash) activateTab(hash);
            else activateTab('overview');

            $('.easyel-nav-tab').click(function (e) {
                e.preventDefault();
                let tab = $(this).data('tab');
                activateTab(tab);
                history.replaceState(null, null, '#'+tab);
            });

            function activateTab(tab) {
                $('.easyel-nav-tab').removeClass('easyel-nav-tab-active');
                $('.easyel-nav-tab[data-tab="' + tab + '"]').addClass('easyel-nav-tab-active');
                $('.easyel-tab-panel').hide();
                $('#tab-' + tab).show();
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu li').removeClass('current');
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a[href*="#' + tab + '"]').parent().addClass('current');
            }
        },

        easyElFilter: function() {
            $(".easyel-action-btn").on("click", function() {
                var filter = $(this).data("filter");
                $(".easyel-action-btn").removeClass("active");
                $(this).addClass("active");

                $(".easy-widget-item,.easyel-extension-item").each(function() {
                    var $widget = $(this);

                    if (filter === "easyel_all") $widget.show();
                    else if (filter === "easyel_free") {
                        if ($widget.hasClass("easyel-pro-widget")) $widget.hide();
                        else $widget.show();
                    } else if (filter === "easyel_pro") {
                        if ($widget.hasClass("easyel-pro-widget")) $widget.show();
                        else $widget.hide();
                    }
                });
            });
        },

        easyelVideoPopuo: function() {
            if ($(".easyel-video-popup").length ) {
                var $popup = $('#easyel-popup-video-area'),
                    $videoContainer = $popup.find('.easyel-popup-video');

                $('.easyel-video-popup').on('click', function(e){
                    e.preventDefault();
                    var videoId = $(this).attr('href').split('v=')[1].split('&')[0];
                    $videoContainer.html('<iframe src="https://www.youtube.com/embed/' + videoId + '?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');
                    $popup.fadeIn();
                });

                $popup.on('click', '.easyel-popup-close, #easyel-popup-video-area', function(e){
                    if($(e.target).is('.easyel-popup-close') || $(e.target).is('#easyel-popup-video-area')){
                        $popup.fadeOut();
                        $videoContainer.html('');
                    }
                });
            };
        },

        easyelFaq: function() {
            if ($(".easyel-faq-item").length ) {
                $(".easyel-faq-item.active").find(".easyel-faq-item-content").show();
                $(".easyel-faq-item-heading").click(function(){
                    var faqItem = $(this).closest(".easyel-faq-item");
                    var content = faqItem.children(".easyel-faq-item-content");

                    if(faqItem.hasClass("active")){
                        faqItem.removeClass("active");
                        content.slideUp(300);
                    } else {
                        $(".easyel-faq-item.active").removeClass("active").children(".easyel-faq-item-content").slideUp(300);
                        faqItem.addClass("active");
                        content.slideDown(300);
                    }
                });
            };
        },
    };

    $(document).ready(function() {
        EasyElementsAdmin.init();
    });

    window.EasyElementsAdmin = EasyElementsAdmin;

})(jQuery);
