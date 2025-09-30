/**
 * Easy Elements Admin JavaScript
 * Handles all admin functionality including AJAX calls, notifications, and interactive features
 */

(function($) {
    'use strict';

    // Main admin object
    var EasyElementsAdmin = {
        
        // Initialize all admin functionality
        init: function() {
            this.initWidgetToggles();
            this.initSearchFunctionality();
            this.initBulkActions();
            this.initAllExtensions();
            this.initNotifications();
            this.easyEltab();
            this.easyElFilter();
            this.easyelVideoPopuo();
            this.easyelFaq();
        },

        // Initialize widget toggle functionality
        initWidgetToggles: function() {
            $('.widget-toggle-checkbox').on('change', function() {
                var checkbox = $(this);
                var widgetKey = checkbox.data('widget-key');
                var tabSlug   = checkbox.data('tab');
                var status = checkbox.is(':checked') ? '1' : '0';
                var statusSpan = checkbox.closest('.widget-toggle').find('.toggle-status');
                
                if (!widgetKey) return;
                
                // Show loading state
                statusSpan.text(easyElementsData.strings.saving).removeClass('error success').show();
                
                $.post(ajaxurl, {
                    action: 'easy_elements_save_widget_setting',
                    widget_key: widgetKey,
                    status: status,
                    tab: tabSlug,
                    nonce: easyElementsData.widget_settings_nonce
                })
                .done(function(response) {
                    if (response.success) {
                        statusSpan.text(easyElementsData.strings.saved).removeClass('error').addClass('success');
                        EasyElementsAdmin.showNotification('Widget setting updated successfully', 'success');
                        setTimeout(function() {
                            statusSpan.fadeOut();
                        }, 2000);
                    } else {
                        statusSpan.text(easyElementsData.strings.error).removeClass('success').addClass('error');
                        checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                        EasyElementsAdmin.showNotification('Failed to update widget setting', 'error');
                    }
                })
                .fail(function() {
                    statusSpan.text('Error!').removeClass('success').addClass('error');
                    checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                    EasyElementsAdmin.showNotification('Network error occurred', 'error');
                });
            });
        },

        // Initialize search functionality
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

        // Initialize bulk actions
        initBulkActions: function() {
            // Bulk activate all
            $('#activate-all-btn').on('click', function() {
                if (confirm(easyElementsData.strings.confirm_activate_all)) {
                    var currentTab = $('.easyel-nav-tab-active').data('tab');
                    EasyElementsAdmin.performBulkAction('activate_all', currentTab );
                }
            });

            // Bulk deactivate all
            $('#deactivate-all-btn').on('click', function() {
                if (confirm(easyElementsData.strings.confirm_deactivate_all)) {
                    EasyElementsAdmin.performBulkAction('deactivate_all');
                }
            });
        },

        // Perform bulk action
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
                                // Free or Pro (active) widgets
                                if(action === 'activate_all') {
                                    $checkbox.prop('checked', true).prop('disabled', false);
                                } else if(action === 'deactivate_all') {
                                    $checkbox.prop('checked', false).prop('disabled', false);
                                }
                            }
                        }
                    });


                    EasyElementsAdmin.showBulkMessage(response.data.message, 'success');
                    EasyElementsAdmin.showNotification(response.data.message, 'success');
                } else {
                    EasyElementsAdmin.showBulkMessage('Error: ' + response.data.message, 'error');
                    EasyElementsAdmin.showNotification('Bulk action failed', 'error');
                }
            })
            .fail(function() {
                EasyElementsAdmin.showBulkMessage('An error occurred while processing the bulk action.', 'error');
                EasyElementsAdmin.showNotification('Network error occurred', 'error');
            })
            .always(function() {
                btn.prop('disabled', false).text(originalText);
            });
        },

        // Show bulk action message
        showBulkMessage: function(message, type) {
            var messageDiv = $('#bulk-action-message');
            messageDiv.removeClass('notice-success notice-error').addClass('notice-' + type).text(message).show();
            
            setTimeout(function() {
                messageDiv.fadeOut();
            }, 5000);
        },

        // Save advance setting
        saveAdvanceSetting: function(action, paramName, checkbox) {
            var checked = checkbox.is(':checked') ? '1' : '0';
            var statusSpan = checkbox.closest('.easy-toggle-switch').next('.setting-status');
            
            // Show saving status
            statusSpan.text(easyElementsData.strings.saving).removeClass('error success').show();
            
            var postData = {};
            postData.action = action;
            postData[paramName] = checked;
            postData.nonce = easyElementsData.advance_settings_nonce;
            
            $.post(ajaxurl, postData)
            .done(function(response) {
                if (response.success) {
                    statusSpan.text(easyElementsData.strings.saved).removeClass('error').addClass('success');
                    EasyElementsAdmin.showNotification('Setting saved successfully', 'success');
                    setTimeout(function() {
                        statusSpan.fadeOut();
                    }, 2000);
                } else {
                    statusSpan.text(easyElementsData.strings.error).removeClass('success').addClass('error');
                    checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                    EasyElementsAdmin.showNotification('Failed to save setting', 'error');
                }
            })
            .fail(function() {
                statusSpan.text(easyElementsData.strings.error).removeClass('success').addClass('error');
                checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                EasyElementsAdmin.showNotification('Network error occurred', 'error');
            });
        },

        initAllExtensions: function () {
            let $extensionsTab = $('.easyel-tab-panel.extensions');

            // Single toggle (same as before)
            $('.easyel-extension-toggle').on('change', function () {
                let checkbox = $(this);
                let key = checkbox.data('key');
                let tab = checkbox.data('tab');
                let status = checkbox.is(':checked') ? 1 : 0;
                let statusSpan = $('<span class="toggle-status"></span>');

                checkbox.closest('.easyel-extension-item').append(statusSpan);
                statusSpan.text('Saving...').show();

                $.post(ajaxurl, {
                    action: 'easy_elements_save_global_extensions',
                    tab: tab,
                    key: key,
                    status: status,
                    nonce: easyElementsData.widget_settings_nonce
                })
                .done(function (response) {
                    if (response.success) {
                        statusSpan.text('Saved').removeClass('error').addClass('success');
                        setTimeout(function () { statusSpan.fadeOut(); }, 2000);
                    } else {
                        statusSpan.text('Error').removeClass('success').addClass('error');
                        checkbox.prop('checked', !checkbox.is(':checked'));
                    }
                })
                .fail(function () {
                    statusSpan.text('Network error').removeClass('success').addClass('error');
                    checkbox.prop('checked', !checkbox.is(':checked'));
                });
            });

            // Group Enable/Disable All
            $extensionsTab.on('change', '.easyel-group-toggle', function () {
                let groupCheckbox = $(this);
                let groupSlug = groupCheckbox.data('group');
                let groupWrapper = $('.easyel-extension-wrapper[data-group="' + groupSlug + '"]');
                let hiddenField = $('input.easyel-group-hidden[name="easy_element_group_' + groupSlug + '"]');

                let status = groupCheckbox.is(':checked') ? 1 : 0;
                let keys = [];

                groupWrapper.find('.easyel-extension-toggle').each(function () {
                    let checkbox = $(this);
                    if (checkbox.closest('.easyel-extension-item').hasClass('easyel-pro-enable')) return;
                    checkbox.prop('checked', status === 1);
                    keys.push(checkbox.data('key'));
                });

                hiddenField.val(status);

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

        // Initialize notifications system
        initNotifications: function() {
            // Create notification container if it doesn't exist
            if ($('#easy-elements-notifications').length === 0) {
                $('body').append('<div id="easy-elements-notifications"></div>');
            }
        },

        // Show notification
        showNotification: function(message, type) {
            var notification = $('<div class="easy-elements-notification ' + type + '">' + message + '</div>');
            $('#easy-elements-notifications').append(notification);
            
            // Show notification
            notification.fadeIn(300);
            
            // Auto hide after 3 seconds
            setTimeout(function() {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        },

        // Utility function to check if element exists
        elementExists: function(selector) {
            return $(selector).length > 0;
        },

        // Utility function to debounce function calls
        debounce: function(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        easyEltab: function () {

            // --------- Admin Menu Submenu Click Handle ---------
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

            // --------- Initial Load ---------
            let hash = window.location.hash.substring(1);
            if (hash) {
                activateTab(hash);
            } else {
                activateTab('overview');
            }

            $('.easyel-nav-tab').click(function (e) {
                e.preventDefault();
                let tab = $(this).data('tab');
                activateTab(tab);
                history.replaceState(null, null, '#'+tab);
            });

            // --------- Tab Active Function ---------
            function activateTab(tab) {
                // Tabs active class
                $('.easyel-nav-tab').removeClass('easyel-nav-tab-active');
                $('.easyel-nav-tab[data-tab="' + tab + '"]').addClass('easyel-nav-tab-active');

                $('.easyel-tab-panel').hide();
                $('#tab-' + tab).show();

                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu li').removeClass('current');
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a[href*="#' + tab + '"]').parent().addClass('current');
            }
        },

        easyelGroupExtension: function() {
            $('.easyel-group-toggle').on('change', function(){
                let checkbox = $(this);
                let group = checkbox.data('group');
                let checked = checkbox.is(':checked');
                // Toggle all checkboxes in this group
                $('.easyel-extension-wrapper[data-group="' + group + '"] .easyel-extension-toggle').each(function(){
                    let cb = $(this);
                    if(!cb.closest('.easyel-extension-item').hasClass('easyel-pro-enable')){
                        cb.prop('checked', checked).trigger('change');
                    }
                });
                // Update hidden input value
                $('.easyel-group-hidden[name="easy_element_group_' + group + '"]').val(checked ? 1 : 0);
                // Optionally, save via AJAX
                $.post(ajaxurl, {
                    action: 'easy_elements_save_group_toggle',
                    group: group,
                    status: checked ? 1 : 0,
                    nonce: easyElementsData.widget_settings_nonce
                });
            });
        },

        easyElFilter: function() {
            $(".easyel-action-btn").on("click", function() {
                var filter = $(this).data("filter");

                // active class handle
                $(".easyel-action-btn").removeClass("active");
                $(this).addClass("active");

                $(".easy-widget-item,.easyel-extension-item").each(function() {
                    var $widget = $(this);

                    if (filter === "easyel_all") {
                        $widget.show();
                    } 
                  
                    else if (filter === "easyel_free") {
                        if ($widget.hasClass("easyel-pro-widget")) {
                            $widget.hide();
                        } else {
                            $widget.show();
                        }
                    } 
                    else if (filter === "easyel_pro") {
                        if ($widget.hasClass("easyel-pro-widget")) {
                            $widget.show();
                        } else {
                            $widget.hide();
                        }
                    }
                });
            });
        },

        // Overview Video Popup 
        easyelVideoPopuo: function() {
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
        },

        // Overview Faq 
        easyelFaq: function() {
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
        },
    };

    // Initialize when document is ready
    $(document).ready(function() {
        EasyElementsAdmin.init();
    });

    // Make it available globally
    window.EasyElementsAdmin = EasyElementsAdmin;


})(jQuery);