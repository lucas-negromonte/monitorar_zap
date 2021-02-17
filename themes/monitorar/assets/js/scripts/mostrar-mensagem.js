$('.btn-mostrar-msg').click(function () {
    if ($(this).attr('data-msg') != '' && $(this).attr('data-msg') != null) {
        $('.mostrar-msg-' + $(this).attr('data-seq')).html($(this).attr('data-msg'));
    }
});