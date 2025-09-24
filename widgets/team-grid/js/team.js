(function($) {
    "use strict";

    function initTeamPopup($scope) {
        // Initialize popup triggers
        $scope.find('.eel-popup-trigger').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var popupId = $(this).data('popup-id');
            var modal = document.getElementById(popupId);
            if(modal) {
                modal.style.display = 'block';
                // Add active class for better styling
                $(modal).addClass('active');
                // Prevent body scroll
                $('body').addClass('eel-popup-open');
            }
        });

        // Initialize close buttons
        $scope.find('.eel-popup-close').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var modal = $(this).closest('.eel-popup-modal');
            modal.hide().removeClass('active');
            $('body').removeClass('eel-popup-open');
        });

        // Close on background click
        $(document).off('click.eelPopup').on('click.eelPopup', function(e) {
            if ($(e.target).hasClass('eel-popup-modal')) {
                $(e.target).hide().removeClass('active');
                $('body').removeClass('eel-popup-open');
            }
        });

        // Close on escape key
        $(document).off('keydown.eelPopup').on('keydown.eelPopup', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                $('.eel-popup-modal.active').hide().removeClass('active');
                $('body').removeClass('eel-popup-open');
            }
        });
    }

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/eel-team-grid.default', initTeamPopup);
    });

    // Also initialize on document ready for non-Elementor pages
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            initTeamPopup($('body'));
        }
    });

})(jQuery);
