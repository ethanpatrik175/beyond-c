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
                    // $("#filepond-script").remove();
                    
                    // unloadScript('../assets/frontend/js/filepond-script.js', 'js');
                    // loadScript('../assets/frontend/js/filepond-script.js', 'js');

                    $('.form-div').html(response);

                    // let r = (Math.random() + 1).toString(36).substring(2);
                    // $(document).find('input[name="filepond"]').attr('id', r);
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
                        // $("#filepond-script").remove();
                        // $.getScript("../assets/frontend/js/filepond-script.js");
                        // unloadScript('../assets/frontend/js/filepond-script.js', 'js');
                        // loadScript('../assets/frontend/js/filepond-script.js', 'js');
                        
                        $('.form-div').html(response);
                        // let r = (Math.random() + 1).toString(36).substring(2);
                        // $(document).find('input[name="filepond"]').attr('id', r);
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

    function countCharacters(element, counter) {
        $('#' + element).keyup(function () {
            var left = 250 - $(this).val().length;
            if (left < 0) {
                left = 0;
            }
            $('.' + counter).text('Characters left: ' + left);
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
