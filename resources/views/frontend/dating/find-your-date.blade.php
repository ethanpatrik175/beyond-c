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
            content: '' !important;
            background: #fff;
        }

        p.detail {
            font-weight: 400;
        }

        .main-container.testimonial-page .testimonial .testimonial-box:hover .text-box::before {
            background: var(--red);
        }

        .testimonial .testimonial-box:hover {
            color: #fff;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/backend/libs/toastr/build/toastr.min.css') }}" />
@endpush

@section('content')
    <div class="main-container testimonial-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>

        <section class="testimonial section-padding">
            <div class="container">
                <div class="row mt-lg-5">
                    <div class="col-sm-2">
                        <ul>
                            <li><a href="#">New Users</a></li>
                            <li><a href="#">New Requests</a></li>
                            <li><a href="#">Sent Requests</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            @forelse ($listUsers as $user)
                                @if (isset($user->dating))
                                    <div class="col-lg-4 mb-3">
                                        <div class="testimonial-box">
                                            <div class="row align-items-center item-{{ $user->id }}">
                                                <div class="col-lg-3 col-3">
                                                    <div class="img-div">
                                                        @if (isset($user->dating->avatar))
                                                            <img src="{{ asset('assets/frontend/images/users/' . $user->id . '/' . Str::of($user->dating->avatar)->replace(' ', '%20')) }}"
                                                                alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                                                class="user-avatar">
                                                        @else
                                                            <img src="{{ asset('assets/frontend/images/user.png') }}"
                                                                alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                                                class="user-avatar" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-9">
                                                    <div class="text-box position-relative">
                                                        <h6>{{ Str::of($user->first_name . ' ' . $user->last_name)->upper() }}
                                                        </h6>
                                                        <p>{{ Str::upper($user->dating->relationship_status) }}</p>
                                                    </div>
                                                    <p class="mb-0 detail">Gender:
                                                        {{ Str::of($user->dating->gender)->ucfirst() ?? '' }}</p>
                                                    <p class="mb-0 detail">Height: {{ $user->dating->height ?? '' }} (cm)
                                                    </p>
                                                    <p class="mb-0 detail">Age:
                                                        {{ \Carbon\Carbon::parse($user->dating->date_of_birth)->age ?? '' }}
                                                        (Yrs)</p>
                                                </div>
                                                <div class="col-lg-3 col-3"></div>
                                                <div class="col-lg-9 col-9">
                                                    <hr class="m-0 p-0" />
                                                    <form class="request-form mt-1" data-item="{{ $user->id }}"
                                                        action="{{ route('dating.send.request') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $user->id }}" />
                                                        @if ($currentUserDating->hasSentFriendRequestTo($user))
                                                            <input type="hidden" name="action"
                                                                id="action{{ $user->id }}" value="unfriend" />
                                                        @else
                                                            <input type="hidden" name="action"
                                                                id="action{{ $user->id }}" value="makefriend" />
                                                        @endif

                                                        @if ($currentUserDating->hasSentFriendRequestTo($user))
                                                            <input type="submit" class="btn btn-danger btn-sm"
                                                                id="btn{{ $user->id }}" value="Cancel Request" />
                                                        @else
                                                            <input type="submit" class="btn btn-primary btn-sm"
                                                                id="btn{{ $user->id }}" value="Send Request" />
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                @endif
                            @empty
                                <div class="col-lg-12 mb-4 text-center">
                                    <h5><a href="javascript:void(0);">No Records Found!</a></h5>
                                </div>
                            @endforelse
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
    <script src="{{ asset('/assets/backend/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/backend/js/pages/toastr.init.js') }}"></script>
    <script src="{{ asset('/assets/frontend/js/dating-friends-script.js') }}"></script>
@endpush
