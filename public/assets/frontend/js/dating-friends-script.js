$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 3000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $(document).on('submit', '.request-form', function (event) {
        event.preventDefault();
        let item = $(this).attr('data-item');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            beforeSend: function () {
                $('.item-' + item).LoadingOverlay("show");
            },
            success: function (response) {
                $('.item-' + item).LoadingOverlay("hide", true);
                $(document).find('#messages').html('');
                if (response.type == "error") {
                    toastr["error"](response.message);
                } else {
                    $('#btn' + item).attr('class', response.btn_class);
                    $('#btn' + item).attr('value', response.btn_txt);
                    $('#action' + item).val(response.action);
                    toastr["success"](response.message);
                    $('.testimonial-box-' + item).remove();
                }
            },
            error: function (response) {
                $('.item-' + item).LoadingOverlay("hide", true);
                $(document).find('#messages').html('');
                $.each(response.responseJSON.errors, function (key, val) {
                    toastr["error"](response.message);
                });
            }
        });
    });

});


function searchFor(event) {
    if ($(document).find('.left-item').hasClass('active')) {
        $(document).find('.left-item').removeClass('active');
    }
    $(event).addClass('active');
    
    $.ajax({
        url: $(event).attr('data-url'),
        type: 'GET',
        beforeSend: function () {
            $(document).find('#results').LoadingOverlay("show");
        },
        success: function (response) {
            $(document).find('#results').LoadingOverlay("hide", true);
            $(document).find('#results').html(response);
        },
        error: function (response) {
            $(document).find('#results').LoadingOverlay("hide", true);
            $(document).find('#messages').html('');
            $.each(response.responseJSON.errors, function (key, val) {
                toastr["error"](response.message);
            });
        }
    });
    // event.preventDefault();
}
