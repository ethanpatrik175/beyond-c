/** profile image / avatar upload scripts */
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
);

/*let r = (Math.random() + 1).toString(36).substring(10);
var input = $('input[name="filepond"]').attr('id');
console.log(input);
*/

var fp = document.querySelector('input[id="avatar"]');
var pond = FilePond.create(fp, {
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    styleLoadIndicatorPosition: 'center bottom',
    styleProgressIndicatorPosition: 'right bottom',
    styleButtonRemoveItemPosition: 'left bottom',
    styleButtonProcessItemPosition: 'right bottom',
    imageCropAspectRatio: '1:1',
});

if($('#userAvatar').data('add-file') !== ''){
    pond.addFile($('#userAvatar').data('add-file'));
}

FilePond.setOptions({
    allowRevert: false,
    server: {
        process: route('dating.upload.image.process'),
        // revert: route('dating.remove.image.process'),
        headers: {
            "X-CSRF-TOKEN": $(document).find('#csrf-token').attr('content'),
        }
    }
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
/** End profile image scripts */