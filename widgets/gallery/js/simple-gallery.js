(function ($) {
    "use strict";

    // Elementor hook
    const initEelGalleryPopup = function ($scope) {
        const $gallery = $scope.find('.eel-gallery-grid.eel-popup-enabled');
        if (!$gallery.length) return;

        let currentIndex = 0;
        const $lightbox = $scope.find('.eel-lightbox');
        const $lightboxImg = $lightbox.find('.eel-lightbox-image');
        const $galleryLinks = $gallery.find('.eel-popup-link');

        function openLightbox(index) {
            currentIndex = index;
            const imgSrc = $galleryLinks.eq(currentIndex).attr('href');
            $lightboxImg.attr('src', imgSrc);
            $lightbox.fadeIn(300).css('display', 'grid');
        }

        function showNext() {
            currentIndex = (currentIndex + 1) % $galleryLinks.length;
            $lightboxImg.attr('src', $galleryLinks.eq(currentIndex).attr('href'));
        }

        function showPrev() {
            currentIndex = (currentIndex - 1 + $galleryLinks.length) % $galleryLinks.length;
            $lightboxImg.attr('src', $galleryLinks.eq(currentIndex).attr('href'));
        }

        $galleryLinks.off('click').on('click', function (e) {
            e.preventDefault();
            const index = $(this).data('index');
            openLightbox(index);
        });

        $lightbox.find('.eel-next').off('click').on('click', showNext);
        $lightbox.find('.eel-prev').off('click').on('click', showPrev);
        $lightbox.find('.eel-close').off('click').on('click', function () {
            $lightbox.fadeOut(200);
        });

        $lightbox.off('click').on('click', function (e) {
            if ($(e.target).is('.eel-lightbox, .eel-close')) {
                $lightbox.fadeOut(200);
            }
        });

        $(document).off('keydown.eelLightbox').on('keydown.eelLightbox', function (e) {
            if ($lightbox.is(':visible')) {
                if (e.key === 'ArrowRight') showNext();
                else if (e.key === 'ArrowLeft') showPrev();
                else if (e.key === 'Escape') $lightbox.fadeOut(200);
            }
        });
    };

    // ✅ Works on both frontend and Elementor editor
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eel-gallery.default',
            initEelGalleryPopup
        );
    });

})(jQuery);
