/** profile image / avatar upload scripts */
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginImageEdit
);

// let r = (Math.random() + 1).toString(36).substring(10);
// var input = $('input[name="filepond"]').attr('id');
var fp = document.querySelector('input[id="avatar"]');

// console.log(input);

var pond = FilePond.create(fp, {
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    styleLoadIndicatorPosition: 'center bottom',
    styleProgressIndicatorPosition: 'right bottom',
    styleButtonRemoveItemPosition: 'left bottom',
    styleButtonProcessItemPosition: 'right bottom',
    imageCropAspectRatio: '1:1',
});

FilePond.setOptions({
    server: {
        process: route('dating.upload.image.process'),
        revert: route('dating.upload.remove.image.process'),
        headers: {
            "X-CSRF-TOKEN": $(document).find('#csrf-token').attr('content'),
        }
    }
});
/** End profile image scripts */