$(document).ready(function () {
    $(document).on('submit', '#request-form', function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            beforeSend: function () {
                $.LoadingOverlay("show");
            },
            success: function (response) {
                $.LoadingOverlay("hide", true);
                $(document).find('#messages').html('');
                if (response.type == "error") {
                    $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-danger">' + response.message + '</div></div>');
                } else {
                    $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-success">' + response.message + '</div></div>');
                }
            },
            error: function (response) {
                $.LoadingOverlay("hide", true);
                $(document).find('#messages').html('');
                $.each(response.responseJSON.errors, function (key, val) {
                    $(document).find('#messages').html('<div class="col-sm-12"> <div class="alert alert-danger">' + val + '</div></div>');
                });
            }
        });
    });
});
