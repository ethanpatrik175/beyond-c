$(document).ready(function () {
    $(document).on('submit', '.dating-form', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function () {
                $('.form-div').LoadingOverlay("show");
            },
            success: function (response) {
                $('.form-div').LoadingOverlay("hide", true);
                console.log(response);
                if (response.type == "error") {
                    $('ul.listErrors').html('<li>' + response.message + '</li>');
                } else if (response.type == "done") {
                    $('.form-div').html(response.html);
                    $('ul.listErrors').html('<li style="font-size: 20px;font-weight: 500;padding: 4px;text-align: justify;">' + response.message + '</li>');
                    setTimeout(() => {
                        window.location.href = route('find.your.date');
                    }, 3000);
                } else {
                    $('.form-div').html(response);
                    $.getScript("../assets/frontend/js/filepond-script.js");
                }
            },
            error: function (response) {
                $('.form-div').LoadingOverlay("hide", true);
                $('ul.listErrors').html('');
                if (response.responseJSON) {
                    $.each(response.responseJSON.errors, function (key, val) {
                        $('ul.listErrors').append('<li>' + val + '</li>');
                    });
                }
            }
        });
    });

    $(document).on('click', '.back', function () {
        let stepStatus = $('input#backStep').val();
        if (stepStatus !== "") {
            $.ajax({
                url: route('dating.step.back'),
                type: 'POST',
                data: {
                    backStep: stepStatus
                },
                beforeSend: function () {
                    $('.form-div').LoadingOverlay("show");
                },
                success: function (response) {
                    $('.form-div').LoadingOverlay("hide", true);
                    if (response.type == "error") {
                        $('ul.listErrors').html('<li>' + response.message + '</li>');
                    } else {
                        $('.form-div').html(response);
                        $.getScript("../assets/frontend/js/filepond-script.js");
                    }
                },
                error: function (response) {
                    $('.form-div').LoadingOverlay("hide", true);
                    $('ul.listErrors').html('');
                    $.each(response.responseJSON.errors, function (key, val) {
                        $('ul.listErrors').append('<li>' + val + '</li>');
                    });
                }
            });
        }
    });

    $(document).on('click', '.skip', function () {
        $('.dating-form').trigger('submit');
    });
});