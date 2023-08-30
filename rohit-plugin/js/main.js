jQuery(document).ready(function($){
    $('.keyword').keyup(function(e){
        var searchdata = $(this).not().val();
        var current = $(this);
        if (jQuery(this).val().length > 0) {
            jQuery(this).next('.loaddata').show()
        
        $.ajax({
            url : ajaxUrl,
            type : "POST",
            data : { action: 'my_search_func', keyword: searchdata },
            success : function(data){
                $(current).next().html(data);     
            }
        })
    } else {
        jQuery(".loaddata").hide();
        jQuery(".loaddata").empty();
    }
    });
});