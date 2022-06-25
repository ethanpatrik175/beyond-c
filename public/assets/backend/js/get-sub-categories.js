$('#category').on('change', function() {
    $.ajax({
        url: route('admin.get.sub.categories'),
        type: "POST",
        cache: false,
        data: { id: $(this).val() },
        beforeSend: function() {
            $('body').addClass('loading')
        },
        success: function(response) {
            $('body').removeClass('loading')
            $('#sub_category').html(response.subCategories);
        },
        error: function(response) {
            $('body').removeClass('loading');
            $('.response-msg').html('');
            $.each(response.responseJSON.errors, function(index, val) {
                $('.response-msg').append(val + '<br />');
            });
            $('.response-msg').removeClass('text-success');
            $('.response-msg').addClass('text-danger');
        }
    });
});