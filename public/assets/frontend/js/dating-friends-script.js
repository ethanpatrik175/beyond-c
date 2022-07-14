$(document).ready(function(){
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
});
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
                // $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-danger alert-dismissible fade show" role="alert">' + response.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>');
            } else {
                $('#btn' + item).attr('class', response.btn_class);
                $('#btn' + item).attr('value', response.btn_txt);
                $('#action' + item).val(response.action);
                toastr["success"](response.message);
                // $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>');
            }
        },
        error: function (response) {
            $('.item-' + item).LoadingOverlay("hide", true);
            $(document).find('#messages').html('');
            $.each(response.responseJSON.errors, function (key, val) {
                toastr["error"](response.message);
                // $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-danger alert-dismissible fade show" role="alert">' + val + ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>');
            });
        }
    });
});

