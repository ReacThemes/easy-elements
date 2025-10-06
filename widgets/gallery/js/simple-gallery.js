(function ($) {
    "use strict";

    $(document).ready(function () {
        const $gallery = $('.eel-gallery-grid.eel-popup-enabled');
        if (!$gallery.length) return;

        let currentIndex = 0;
        const $lightbox = $('.eel-lightbox');
        const $lightboxImg = $lightbox.find('.eel-lightbox-image');
        const $galleryLinks = $gallery.find('.eel-popup-link');

        // Function to open lightbox with specific index
        function openLightbox(index) {
            currentIndex = index; // ✅ Fix: Always set correct index first
            const imgSrc = $galleryLinks.eq(currentIndex).attr('href');
            $lightboxImg.attr('src', imgSrc);
            $lightbox.fadeIn(300).css('display', 'grid');
        }

        // Function to show next image
        function showNext() {
            currentIndex = (currentIndex + 1) % $galleryLinks.length;
            $lightboxImg.attr('src', $galleryLinks.eq(currentIndex).attr('href'));
        }

        // Function to show previous image
        function showPrev() {
            currentIndex = (currentIndex - 1 + $galleryLinks.length) % $galleryLinks.length;
            $lightboxImg.attr('src', $galleryLinks.eq(currentIndex).attr('href'));
        }

        // Open lightbox on image click
        $galleryLinks.on('click', function (e) {
            e.preventDefault();
            const index = $(this).data('index'); // ✅ Get data-index correctly
            openLightbox(index);
        });

        // Navigation
        $lightbox.find('.eel-next').on('click', showNext);
        $lightbox.find('.eel-prev').on('click', showPrev);

        // Close button
        $lightbox.find('.eel-close').on('click', function () {
            $lightbox.fadeOut(200);
        });

        // Close when clicking outside image
        $lightbox.on('click', function (e) {
            if ($(e.target).is('.eel-lightbox, .eel-close')) {
                $lightbox.fadeOut(200);
            }
        });

        // Keyboard navigation
        $(document).on('keydown', function (e) {
            if ($lightbox.is(':visible')) {
                if (e.key === 'ArrowRight') showNext();
                else if (e.key === 'ArrowLeft') showPrev();
                else if (e.key === 'Escape') $lightbox.fadeOut(200);
            }
        });
    });

})(jQuery);
