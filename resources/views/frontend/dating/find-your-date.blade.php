@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        /* .user-avatar {
                                                width: auto;
                                                height: 150px !important;
                                                margin: 0 auto;
                                                background-color: #000;
                                                padding: 10px;
                                                border-top-left-radius: 0px;
                                                border-bottom-left-radius: 0px
                                            } */

        /* .main-container.events-page .events-archive .events-box .event-right-box {
                                        padding: 0px 15px;
                                    }

                                    .main-container.events-page .events-archive .events-box .event-right-box .event-details p.detail {
                                        font-size: 14px;
                                        font-weight: 600;
                                    }

                                    .main-container.events-page .events-archive .events-box .event-comments {
                                        right: 30px;
                                        bottom: 15px;
                                    }

                                    .main-container.events-page .events-archive .events-box .event-share {
                                        top: 15px;
                                    }

                                    .fa-message,
                                    .fa-user-plus {
                                        color: #e50000 !important;
                                        font-size: 20px !important;
                                    }

                                    #form-submit {
                                        background: none;
                                        padding: 0px;
                                    }

                                    .main-container.events-page .events-archive .events-box .event-right-box {
                                        border-top-right-radius: 0px;
                                        border-bottom-right-radius: 0px;
                                    }

                                    .main-container.events-page .events-archive .events-box .event-left-box img {
                                        border-top-left-radius: 0px;
                                        border-bottom-left-radius: 0px;
                                    } */

        /* .alert.alert-success.alert-dismissible.fade.show {
                                                                            position: fixed;
                                                                            bottom: 50px;
                                                                            left: 50%;
                                                                            transform: translate(-50%);
                                                                            height: 70px;
                                                                            display: flex;
                                                                            align-items: center;
                                                                            z-index: 9999;
                                                                            background: darkgreen;
                                                                            color: white;
                                                                            text-align: center !important;
                                                                            padding: 0px !important;
                                                                            max-width: 450px !important;
                                                                            width: 100%;
                                                                            justify-content: center;
                                                                        } */

        section.section-padding {
            padding: 20px 0px 50px 0px;
        }

        .testimonial .testimonial-box .text-box::before {
            all: unset !important;
        }

        p.detail {
            font-weight: 400;
        }

        .main-container.testimonial-page .testimonial .testimonial-box:hover .text-box {
            color: #000 !important;
        }

        .main-container.testimonial-page .testimonial .testimonial-box:hover .text-box::before {
            background: var(--red);
        }

        .testimonial .testimonial-box:hover {
            color: #000 !important;
            background: #fff !important;
        }

        .main-container.testimonial-page .testimonial .testimonial-box:hover .text-box>h6,
        .main-container.testimonial-page .testimonial .testimonial-box:hover p:first-child {
            color: #000;
        }

        .sidebar a{
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }

        .red-c{
            color: var(--red);
        }

        a.left-item.active {
            color: var(--red);
        }

    </style>
    <link rel="stylesheet" href="{{ asset('assets/backend/libs/toastr/build/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/backend/libs/sweetalert2/sweetalert2.min.css')}}" />
@endpush

@section('content')
    <div class="main-container testimonial-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>

        <section class="testimonial section-padding">
            <div class="container">
                <div class="row mt-lg-5">
                    <div class="col-sm-2 mb-3">
                        <div class="testimonial-box sidebar">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-12">
                                    <h6>Quick Links</h6>
                                    <hr class="mt-0" />
                                </div>
                                <div class="col-lg-12 col-12">
                                    <p class="mb-2"><i class="fa fa-users red-c"></i> <a href="javascript:void(0);" data-url="{{route('dating.make.friends')}}" class="left-item active" onClick="searchFor(this)">Make Friends</a></p>
                                    <p class="mb-2"><i class="fa fa-users red-c"></i> <a href="javascript:void(0);" data-url="{{route('dating.my.friends')}}" class="left-item" onClick="searchFor(this)">My Friends</a></p>
                                    <p class="mb-2"><i class="fa fa-user-plus red-c"></i> <a href="javascript:void(0);" data-url="{{route('dating.new.requests')}}" class="left-item" onClick="searchFor(this)">New Requests</a></p>
                                    <p class="mb-2"><i class="fa fa-share-square red-c"></i> <a href="javascript:void(0);" data-url="{{route('dating.sent.requests')}}" class="left-item" onClick="searchFor(this)">Sent Requests</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10" id="results">
                        @include('frontend.dating.make-friends')
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
    <script src="{{ asset('/assets/backend/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/backend/js/pages/toastr.init.js') }}"></script>
    <script src="{{asset('assets/backend/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('/assets/frontend/js/dating-friends-script.js') }}"></script>
@endpush
