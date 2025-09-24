(function($){
    "use strict";
    $(document).ready(function(){
        $('.eel-testimonial-more-btn').on('click', function(e){
            e.preventDefault();
            $('.eel-hidden-testimonial').removeClass('eel-hidden-testimonial');
            $('.elementor-element').removeClass('testifull-overlay');
            $(this).hide();
        });
    });
})(jQuery);
