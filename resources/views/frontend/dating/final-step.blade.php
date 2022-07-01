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

<form method="POST" action="{{ route('dating.final.step.process') }}" class="needs-validation dating-form"
    enctype="multipart/form-data" novalidate>
    @csrf
    <input type="hidden" id="userAvatar"
        @if (Session::has('avatar')) data-add-file="{{ asset('assets/frontend/images/users/' . auth()->user()->id . '/' . Session::get('avatar')) }}" @else data-add-file="" @endif>
    <input type="hidden" id="backStep" value="step-four" />
    <div class="section-heading">
        <h4>Create Account - Last Step</h4>
        <hr class="text-danger mb-1" />
    </div>
    <div class="row">
        <ul class="text-danger listErrors text-start mb-2"></ul>
        <div class="col-lg-12 mt-2">
            <div class="form-group text-start">
                <label for="avatar" class="black-text mb-1">Avatar</label>
                <input type="file" name="filepond" class="filepond demo-profile-picture" id="avatar"
                    accept="image/png, image/jpeg, image/gif"
                    data-label-idle="Drag &amp; Drop your picture or <span class='filepond--label-action'>Browse</span>"
                    data-instant-upload="false" data-drop-validation="true" data-image-preview-height="170"
                    data-image-crop-aspect-ratio="1:1" data-style-panel-layout="compact circle"
                    data-style-load-indicator-position="center bottom"
                    data-style-progress-indicator-position="right bottom"
                    data-style-button-process-item-position="right bottom">
            </div>
        </div>
    </div>
    <div class="mt-2">
        <button type="submit" class="red-button">Finish</button>
        <div class="row mt-2">
            <div class="col-sm-6 text-start"><a href="javascript:void(0);" class="back">BACK</a></div>
            <div class="col-sm-6 text-end skip"></div>
        </div>
    </div>
</form>
