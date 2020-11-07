body = $(document);

body.on('click', '.wg-box-item', function() {
    $('.wg-box-item').removeClass('active');
    $(this).addClass('active');
    $(this).closest('.row').find('input').val($(this).attr('data-value'));
});


//input col
body.on('click', '.input-col-wrap .col-item', function() {
    var col = $(this).attr('data-col');
    $(this).closest('.input-col-wrap').removeClass().addClass('input-col-wrap input-col-' + col);
    $(this).closest('.input-cols').find('input').val(col);
});