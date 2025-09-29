(function($){
    $(document).ready(function(){
        $('.easyel-tooltip').on('mouseenter click', function(e){
            e.stopPropagation();
            $(this).addClass('show');
        });

        $('.easyel-tooltip').on('mouseleave', function(){
            $(this).removeClass('show');
        });

        $(document).on('click', function(){
            $('.easyel-tooltip').removeClass('show');
        });
    });
})(jQuery);
