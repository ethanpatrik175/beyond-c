{{-- <form action="">
    <div class="section-heading">
        <h4>Create Account - Last Step</h4>
        <hr class="text-danger mb-1" />
    </div>
</form>
<div class="row">
    <div class="col-sm-12">
        
        <form method="post" action="{{ route('dating.upload.image.process') }}" enctype="multipart/form-data"
            class="dropzone" id="dropzone">
            @csrf
        </form>
    </div>
</div> --}}

<form method="POST" action="{{ route('dating.step.four.process') }}" class="needs-validation dating-form"
    enctype="multipart/form-data" novalidate>
    @csrf
    <input type="hidden" id="backStep" value="step-three" />
    <div class="section-heading">
        <h4>Create Account - Last Step</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <input type="file" name="filepond" class="filepond demo-profile-picture" id="avatar"
                    accept="image/png, image/jpeg, image/gif"
                    data-label-idle="Drag &amp; Drop your picture or <span class='filepond--label-action'>Browse</span>"
                    data-instant-upload="false" data-drop-validation="true" data-image-preview-height="170"
                    data-image-crop-aspect-ratio="1:1" data-style-panel-layout="compact circle"
                    data-style-load-indicator-position="center bottom"
                    data-style-progress-indicator-position="right bottom"
                    data-style-button-remove-item-position="left bottom"
                    data-style-button-process-item-position="right bottom">
            </div>
        </div>
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <label for="avatar" class="black-text mb-1">Avatar</label>
                <input type="file" name="avatar" id="avatar" />
                <div class="invalid-feedback">
                    Upload your avatar here
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <label for="more_images" class="black-text mb-1">More Images</label>
                <input type="file" name="more_images[]" id="more_images" />
                <div class="invalid-feedback">
                    Upload your more images here
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <button type="submit" class="red-button">Next </button>
        <div class="row mt-2">
            <div class="col-sm-6 text-start"><a href="javascript:void(0);" class="back">BACK</a></div>
            <div class="col-sm-6 text-end skip">
                <a href="javascript:void(0);" class="skip">SKIP</a>
            </div>
        </div>
        {{-- <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
            Cookies Policy.</p> --}}
    </div>
</form>
