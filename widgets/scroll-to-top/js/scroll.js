(function($) {
    "use strict";
    $(document).ready(function(){
        var win=$(window);
        var totop = $('#easyel-top-to-bottom');    
        win.on('scroll', function() {
            if (win.scrollTop() > 150) {
                totop.fadeIn();
                $('header').addClass('eel-scoll-to-top');
                $('#easyel-top-to-bottom').addClass('eel-scroll-visible');  
            } else {
                totop.slideDown(400);
                $('header').removeClass('eel-scoll-to-top');
                $('#easyel-top-to-bottom').removeClass('eel-scroll-visible');
                
            }
        });
        totop.on('click', function() {
            $("html,body").animate({
                scrollTop: 0
            }, 500)
        }); 
    });
})(jQuery);
