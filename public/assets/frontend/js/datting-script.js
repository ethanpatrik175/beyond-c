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

                if (response.type == "error") {
                    $('ul.listErrors').html('<li>' + response.message + '</li>');
                } else {
                    $('.form-div').html(response);
                }
            },
            error: function (response) {

                $('.form-div').LoadingOverlay("hide", true);
                $('ul.listErrors').html('');

                if(response.responseJSON)
                {
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

    function countCharacters(element, counter)
    {
        $('#' + element).keyup(function () {
            var left = 250 - $(this).val().length;
            if (left < 0) {
                left = 0;
            }
            $('.'+counter).text('Characters left: ' + left);
        });
    }

    countCharacters('passion', 'passion-counter');
    countCharacters('about', 'about-counter');



    /*let stepStatus = $('input#step-status').val();
    if (stepStatus !== "") {
        $.ajax({
            url: route('dating.restore.step'),
            type: 'POST',
            beforeSend: function () {
                $('.form-div').LoadingOverlay("show");
            },
            success: function (response) {
                $('.form-div').LoadingOverlay("hide", true);

                if (response.type == "error") {
                    $('ul.listErrors').html('<li>' + response.message + '</li>');
                } else {
                    $('.form-div').html(response);
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
    } */

});
