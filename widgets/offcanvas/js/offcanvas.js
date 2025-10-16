(function($){
    // Toggle offcanvas
    $(document).on('click', '.eel-offcanvas-toggle', function(e){
        e.stopPropagation();
        var target = $(this).data('target');
        $(target).toggleClass('active'); 
        $('body').toggleClass('eel-offcanvas-active');
    });

    // Click outside to close
    $(document).on('click', function(){
        $('.eel-offcanvas').removeClass('active');
        $('body').removeClass('eel-offcanvas-active');
    });

    // Prevent closing when clicking inside offcanvas
    $(document).on('click', '.eel-offcanvas', function(e){
        e.stopPropagation();
    });
})(jQuery);
