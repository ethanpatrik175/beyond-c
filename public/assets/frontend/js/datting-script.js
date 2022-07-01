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
                }
                else if(response.type == "done"){
                    $('.form-div').html(response.html);
                    $('ul.listErrors').html('<li style="font-size: 20px;font-weight: 500;padding: 4px;text-align: justify;">' + response.message + '</li>');                    
                    setTimeout(() => {
                        window.location.href=route('find.your.date');
                    }, 3000);
                }
                else {
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

    // Dropzone.options.dropzone = {
    //     maxFiles: 1,
    //     renameFile: function (file) {
    //         var dt = new Date();
    //         var time = dt.getTime();
    //         return time + file.name;
    //     },
    //     acceptedFiles: ".jpeg,.jpg,.png",
    //     addRemoveLinks: true,
    //     timeout: 5000,
    //     removedfile: function (file) {
    //         var name = file.upload.filename;
    //         $.ajax({
    //             type: 'POST',
    //             url: route('dating.remove.image.process'),
    //             data: {
    //                 filename: name
    //             },
    //             success: function (data) {
    //                 console.log("File has been successfully removed!!");
    //             },
    //             error: function (e) {
    //                 console.log(e);
    //             }
    //         });
    //         var fileRef;
    //         return (fileRef = file.previewElement) != null ?
    //             fileRef.parentNode.removeChild(file.previewElement) : void 0;
    //     },
    //     success: function (file, response) {
    //         console.log(response);
    //     },
    //     error: function (file, response) {
    //         return false;
    //     },
    //     init: function () {
    //         this.on("maxfilesexceeded", function (file) {
    //             // this.removeFile(file);
    //             showAlert("File Limit exceeded!", "error");
    //         });
    //     }
    // };

});
