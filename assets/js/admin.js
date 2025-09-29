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
        },

        // Initialize widget toggle functionality
        initWidgetToggles: function() {
            $('.widget-toggle-checkbox').on('change', function() {
                var checkbox = $(this);
                var widgetKey = checkbox.data('widget-key');
                var status = checkbox.is(':checked') ? '1' : '0';
                var statusSpan = checkbox.closest('.widget-toggle').find('.toggle-status');
                
                if (!widgetKey) return;
                
                // Show loading state
                statusSpan.text(easyElementsData.strings.saving).removeClass('error success').show();
                
                $.post(ajaxurl, {
                    action: 'easy_elements_save_widget_setting',
                    widget_key: widgetKey,
                    status: status,
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
                EasyElementsAdmin.performBulkAction('activate_all');
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
            var btn = action === 'activate_all' ? $('#activate-all-btn') : $('#deactivate-all-btn');
            var originalText = btn.text();
            
            btn.prop('disabled', true).text(easyElementsData.strings.processing);
            
            $.post(ajaxurl, {
                action: 'easy_elements_bulk_action',
                bulk_action: action,
                nonce: easyElementsData.bulk_action_nonce
            })
            .done(function(response) {
                if (response.success) {
                    // Update all checkboxes

                    $('.widget-toggle-checkbox').each(function() {
                        let $checkbox = $(this);
                        let isPro = $checkbox.closest('.easy-widget-item').hasClass('easyel-pro-enable');

                        if (isPro) {
                            if (!response.data.is_pro_active) {
                                $checkbox.prop('checked', false).prop('disabled', true);
                            }
                        } else {
                            $checkbox.prop('checked', action === 'activate_all' ? true : false);
                            $checkbox.prop('disabled', false);
                        }
                    });

                    // Show success message
                    EasyElementsAdmin.showBulkMessage(response.data.message, 'success');
                    EasyElementsAdmin.showNotification(response.data.message, 'success');
                    
                    // Update status spans
                    $('.toggle-status').text(easyElementsData.strings.updated).removeClass('error').addClass('success');
                    setTimeout(function() {
                        $('.toggle-status').fadeOut();
                    }, 3000);
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

        // Initialize all extensions
        initAllExtensions: function() {
            var checkbox = $('#easyel_enable_js_animation');
            if (checkbox.length === 0) return;
            
            // Create notification span
            var notification = $('<span class="js-animation-status"></span>');
            notification.css({
                'marginLeft': '10px',
                'fontWeight': 'bold',
                'display': 'none'
            });
            checkbox.closest('td').append(notification);
            
            checkbox.on('change', function() {
                var checkbox = $(this);
                var value = checkbox.is(':checked') ? '1' : '0';
                
                // Show saving status
                notification.text(easyElementsData.strings.saving).css('color', '#2196F3').show();
                
                $.post(ajaxurl, {
                    action: 'easyel_save_js_animation',
                    value: value,
                    nonce: easyElementsData.js_animation_nonce
                })
                .done(function(response) {
                    if (response.success) {
                        notification.text(easyElementsData.strings.saved).css('color', '#4CAF50');
                        EasyElementsAdmin.showNotification('JS Animation setting saved', 'success');
                        setTimeout(function() {
                            notification.fadeOut();
                        }, 2000);
                    } else {
                        notification.text(easyElementsData.strings.error).css('color', '#f44336');
                        checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                        EasyElementsAdmin.showNotification('Failed to save JS Animation setting', 'error');
                        setTimeout(function() {
                            notification.fadeOut();
                        }, 3000);
                    }
                })
                .fail(function() {
                    notification.text(easyElementsData.strings.error).css('color', '#f44336');
                    checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox
                    EasyElementsAdmin.showNotification('Network error occurred', 'error');
                    setTimeout(function() {
                        notification.fadeOut();
                    }, 3000);
                });
            });

            // Cursor toggle
            var cursorCheckbox = $('#easyel_enable_cursor');
            if (cursorCheckbox.length) {
                var cursorNotification = $('<span class="cursor-status"></span>');
                cursorNotification.css({
                    'marginLeft': '10px',
                    'fontWeight': 'bold',
                    'display': 'none'
                });
                cursorCheckbox.closest('td').append(cursorNotification);

                cursorCheckbox.on('change', function() {
                    var el = $(this);
                    var value = el.is(':checked') ? '1' : '0';

                    cursorNotification.text(easyElementsData.strings.saving).css('color', '#2196F3').show();

                    $.post(ajaxurl, {
                        action: 'easyel_save_cursor',
                        value: value,
                        nonce: easyElementsData.nonce
                    })
                    .done(function(response) {
                        if (response.success) {
                            cursorNotification.text(easyElementsData.strings.saved).css('color', '#4CAF50');
                            EasyElementsAdmin.showNotification('Cursor setting saved', 'success');
                            setTimeout(function() {
                                cursorNotification.fadeOut();
                            }, 2000);
                        } else {
                            cursorNotification.text(easyElementsData.strings.error).css('color', '#f44336');
                            el.prop('checked', !el.is(':checked'));
                            EasyElementsAdmin.showNotification('Failed to save Cursor setting', 'error');
                            setTimeout(function() {
                                cursorNotification.fadeOut();
                            }, 3000);
                        }
                    })
                    .fail(function() {
                        cursorNotification.text(easyElementsData.strings.error).css('color', '#f44336');
                        el.prop('checked', !el.is(':checked'));
                        EasyElementsAdmin.showNotification('Network error occurred', 'error');
                        setTimeout(function() {
                            cursorNotification.fadeOut();
                        }, 3000);
                    });
                });
            }
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

                if (href.includes('easy-elements-dashboard')) {
                    e.preventDefault();

                    let tab = 'overview';
                    if (href.includes('#widget')) tab = 'widget';
                    if (href.includes('#extensions')) tab = 'extensions';
                    if (href.includes('#advsettings')) tab = 'advsettings';

                    window.location.hash = tab;
                    activateTab(tab);
                }
            });

            var hash = window.location.hash.substring(1);
            if (hash) {
                activateTab(hash);
            } else {
                activateTab('overview'); 
            }

            $('.easyel-nav-tab').click(function (e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                activateTab(tab);
                history.replaceState(null, null, '#'+tab); 
            });

            // --------- Tab Active Function ---------
            function activateTab(tab) {
                // Tabs active class
                $('.easyel-nav-tab').removeClass('easyel-nav-tab-active');
                $('.easyel-nav-tab[data-tab="' + tab + '"]').addClass('easyel-nav-tab-active');

                // Panels show/hide
                $('.easyel-tab-panel').hide();
                $('#tab-' + tab).show();

                // Admin submenu active state sync
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu li').removeClass('current');
                $('#toplevel_page_easy-elements-dashboard ul.wp-submenu a[href*="#' + tab + '"]').parent().addClass('current');
            }
        },

        easyElFilter: function() {
            $(".easyel-action-btn").on("click", function() {
                var filter = $(this).data("filter");

                // active class handle
                $(".easyel-action-btn").removeClass("active");
                $(this).addClass("active");

                $(".easy-widget-item").each(function() {
                    var $widget = $(this);

                 
                    if (filter === "easyel_all") {
                        $widget.show();
                    } 
                  
                    else if (filter === "easyel_free") {
                        if ($widget.hasClass("easyel-pro-enable")) {
                            $widget.hide();
                        } else {
                            $widget.show();
                        }
                    } 
                    else if (filter === "easyel_pro") {
                        if ($widget.hasClass("easyel-pro-enable")) {
                            $widget.show();
                        } else {
                            $widget.hide();
                        }
                    }
                });
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        EasyElementsAdmin.init();
    });

    // Make it available globally
    window.EasyElementsAdmin = EasyElementsAdmin;

})(jQuery);