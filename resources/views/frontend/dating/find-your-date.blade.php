@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        .user-avatar {
            width: auto;
            height: 150px !important;
            margin: 0 auto;
            background-color: #000;
            padding: 10px;
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px
        }

        .main-container.events-page .events-archive .events-box .event-right-box {
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
        }

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
    </style>
    <link rel="stylesheet" href="{{ asset('assets/backend/libs/toastr/build/toastr.min.css') }}" />
@endpush

@section('content')
    <div class="main-container events-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="events-archive pb-3 pt-5 pt-lg-0">
            <div class="container">
                <div class="row pt-5" id="messages"></div>
                <div class="row">
                    @if (Session::has('message'))
                        <div class="col-sm-12 mb-4">
                            <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show" role="alert">
                                @if (Session::get('type') == 'danger')
                                    <i class="mdi mdi-block-helper me-2"></i>
                                @else
                                    <i class="mdi mdi-check-all me-2"></i>
                                @endif
                                {{ __(Session::get('message')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="col-sm-12 mb-4">
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-block-helper me-2"></i>
                                    {{ __($error) }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @forelse ($listUsers as $user)
                        @if (isset($user->dating))
                            <div class="col-sm-4 mb-4">
                                <div class="events-box d-lg-flex position-relative item-{{ $user->id }}">
                                    <div class="event-left-box position-relative">
                                        @if (isset($user->dating->avatar))
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('assets/frontend/images/users/' . $user->id . '/' . Str::of($user->dating->avatar)->replace(' ', '%20')) }}"
                                                    alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                                    class="user-avatar">
                                            </a>
                                        @else
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('assets/frontend/images/user.png') }}"
                                                    alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}"
                                                    class="user-avatar">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="event-right-box d-flex flex-column justify-content-center">
                                        <div class="event-details">
                                            <h5>{{ Str::of($user->first_name . ' ' . $user->last_name)->ucfirst() }}</h5>
                                            <p class="mb-0 detail">Gender:
                                                {{ Str::of($user->dating->gender)->ucfirst() ?? '' }}</p>
                                            <p class="mb-0 detail">Relationship:
                                                {{ Str::of($user->dating->relationship_status)->ucfirst() ?? '' }}</p>
                                                <p class="mb-0 detail">Height:
                                                    {{ Str::of($user->dating->relationship_status)->ucfirst() ?? '' }}</p>
                                                    <p class="mb-0 detail">DOB: {{ $user->dating->date_of_birth }}</p>
                                        </div>
                                    </div>
                                    <div class="event-comments">
                                        <a href="javascript:void(0);"><i class="fa-solid fa-message"></i></a>
                                    </div>
                                    <div class="event-share">
                                        <form class="request-form" data-item="{{ $user->id }}"
                                            action="{{ route('dating.send.request') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}" />

                                            @if ($currentUserDating->hasSentFriendRequestTo($user))
                                                <input type="hidden" name="action" id="action{{ $user->id }}"
                                                    value="unfriend" />
                                            @else
                                                <input type="hidden" name="action" id="action{{ $user->id }}"
                                                    value="makefriend" />
                                            @endif

                                            <button type="submit" id="form-submit">
                                                @if ($currentUserDating->hasSentFriendRequestTo($user))
                                                    <i class="fa-solid fa-user-check" id="icon{{ $user->id }}"></i>
                                                @else
                                                    <i class="fa-solid fa-user-plus" id="icon{{ $user->id }}"></i>
                                                @endif
                                            </button>

                                        </form>
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

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
                <!-- Position it -->
                <div style="position: absolute; top: 0; right: 0;">

                    <!-- Then put toasts within -->
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <img src="..." class="rounded mr-2" alt="...">
                            <strong class="mr-auto">Bootstrap</strong>
                            <small class="text-muted">just now</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            See? Just like this.
                        </div>
                    </div>

                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <img src="..." class="rounded mr-2" alt="...">
                            <strong class="mr-auto">Bootstrap</strong>
                            <small class="text-muted">2 seconds ago</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            Heads up, toasts will stack automatically
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
