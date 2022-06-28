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

        .dz-message>span{
            color: rgb(255, 0, 0);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
    <script src="{{ asset('assets/frontend/js/datting-script.js') }}"></script>
    <script>

    </script>
@endpush