/** profile image / avatar upload scripts */
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginImageEdit
);

const input = document.querySelector('input[id="avatar"]');
const pond = FilePond.create(input, {
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
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }
});
/** End profile image scripts */