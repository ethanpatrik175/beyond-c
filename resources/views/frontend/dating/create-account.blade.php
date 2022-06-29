@extends('layouts.frontend.master')

@section('title')
    {{ 'Create Account' }}
@endsection

@push('styles')
    <style>
        .parsley-required,
        .parsley-equalto {
            color: red;
        }

        .invalid-feedback {
            font-weight: bold !important;
        }

        .red-button {
            color: #ffffff;
        }

        .dz-message>span {
            color: rgb(255, 0, 0);
        }
    </style>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" /> --}}
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="main-container volunteer-signup-page">
        <x-mobile-view-slide />

        <section class="home-banner">
            <div class="container center-container">
                <div class="row align-items-center">
                    <div class="col-lg-5 offset-lg-1">
                        <div class="section-heading">
                            <h5>Welcome To Engaging Singles</h5>
                            <h1>Are You Waiting For <span>Dating?</span></h1>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br /> Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s</p>
                        </div>

                        <div class="count-up d-flex justify-content-center justify-content-md-start mt-4">
                            <div class="left-count text-center">
                                <h4>10M+</h4>
                                <p>Active Datings</p>
                            </div>
                            <div class="right-count text-center">
                                <h4>150M+</h4>
                                <p>Events Booking</p>
                            </div>
                        </div>
                    </div>
                    <div class="offset-lg-1 d-none d-lg-block col-lg-4">
                        <div class="form-div p-3">
                            @if (Session::has('stepStatus'))
                                @include(Session::get('stepStatus'));
                            @else
                                @include('frontend.dating.step-one')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-footer />
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
    </script>

    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
    <!-- form mask -->
    <script src="{{ asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- form mask init -->
    <script src="{{ asset('assets/backend/js/pages/form-mask.init.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script> --}}
    <script src="{{ asset('assets/frontend/js/datting-script.js') }}"></script>
    <script></script>
    <!-- include jQuery library -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script> --}}

    <!-- include FilePond library -->
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- include FilePond plugins -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <!-- include FilePond jQuery adapter -->
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}

    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js">
    </script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js">
    </script>
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
    {{-- <script src="https://unpkg.com/filepond-plugin-image-filter/dist/filepond-plugin-image-filter.js"></script> --}}
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}



    <script>
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImageCrop);
        FilePond.registerPlugin(FilePondPluginImageEdit);
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageResize);
        FilePond.registerPlugin(FilePondPluginImageTransform);
        FilePond.registerPlugin(FilePondPluginImageValidateSize);
        FilePond.registerPlugin(FilePondPluginImageExifOrientation);
        // FilePond.registerPlugin(FilePondPluginImageFilter);
       
        const input = document.querySelector('input[id="avatar"]');


        // create a FilePond instance at the fieldset element location
        const pond = FilePond.create(input);
       
        FilePond.setOptions({
            server: {
                process: route('dating.upload.image.process'),
                revert: route('dating.upload.remove.image.process'),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        })
    </script>
@endpush
