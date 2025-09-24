(function($) {
    "use strict";
    function handle_faq_($scope) {
        const $faqItems = $scope.find('.eel-faq-item');
        $faqItems.each(function () {
            const $item = $(this);
            const $question = $item.find('.eel-faq-question');
            const $answer = $item.find('.eel-faq-answer');
            $question.off('click.faqToggle');
            if ($item.hasClass('active')) {
                $answer.css('max-height', $answer[0].scrollHeight + 'px');
            } else {
                $answer.css('max-height', '0');
            }
            $question.on('click.faqToggle', function () {
                const isActive = $item.hasClass('active');
                $faqItems.removeClass('active')
                         .find('.eel-faq-answer')
                         .css('max-height', '0');

                if (!isActive) {
                    $item.addClass('active');
                    $answer.css('max-height', $answer[0].scrollHeight + 'px');
                }
            });
        });
    }

    function checkStickySection() {
        const stickySection = document.querySelector('.eel-faq-sticky');
        document.body.classList.toggle('sticky-enabled-overlap-faq', !!stickySection);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', handle_faq_);
        elementorFrontend.hooks.addAction('frontend/element_ready/eel-faq-accordion.default', handle_faq_);

        checkStickySection();

        const observer = new MutationObserver(checkStickySection);
        observer.observe(document.body, { childList: true, subtree: true });
    });

})(jQuery);
