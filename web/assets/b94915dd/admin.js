function onclick_tab(obj,name) {
    $(obj).siblings().removeClass('active');
    $(obj).addClass('active');
    $( '#admin_tab' ).load( $(obj).attr('data-url') );
} 

$(document).on('click','.list-group-item',function() {
    if ($(this).attr('target') != "main") {
        return;
    }
    $('.list-group-item').removeClass('active');
    $(this).addClass('active');
});
