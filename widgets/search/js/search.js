(function($) {
    "use strict";
    $(document).ready(function(){
        $('.eel-search-open-btn').on('click', function(e){
            e.preventDefault(); 
            e.stopPropagation();
            $('.eel-search-lightbox').addClass('eel-lightbox');
            setTimeout(function() {
                $('.eel-search-lightbox .eel-search-field').focus();
            }, 500); 
        });
        // Close search
        $('.eel-search-close-btn, .eel-search-overlay').on('click', function(e){
            e.preventDefault();  
            e.stopPropagation();
            $('.eel-search-lightbox').removeClass('eel-lightbox');
        });

    });
})(jQuery);
