@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        .user-avatar{
            width: 100px;
            height: 100px !important;
            margin: 0 auto;
            background-color: #000;
            padding: 10px;
        }

        .main-container.events-page .events-archive .events-box .event-right-box{
            padding: 0px 15px;
        }

        .main-container.events-page .events-archive .events-box .event-right-box .event-details p.detail{
            font-size: 14px;
            font-weight: 600;
        }

        .main-container.events-page .events-archive .events-box .event-comments{
            right: 30px;
            bottom: 15px;
        }

        .main-container.events-page .events-archive .events-box .event-share{
            top: 15px;
        }

        .fa-message, .fa-user-plus{
            color: #e50000 !important;
            font-size: 20px !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-container events-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="events-archive pb-3 pt-5 pt-lg-0">
            <div class="container">

                <div class="row pt-5 justify-content-center">
                    @if (Session::has('message'))
                        <div class="col-sm-12 mb-4">
                            <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show" role="alert">
                                @if (Session::get('type') == 'danger')
                                    <i class="mdi mdi-block-helper me-2"></i>
                                @else
                                    <i class="mdi mdi-check-all me-2"></i>
                                @endif
                                {{ __(Session::get('message')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                <div class="events-box d-lg-flex position-relative">
                                    <div class="event-left-box position-relative">
                                        {{-- <div class="event-date">
                                            <p class="m-0 text-white">
                                                {{ date('d-M-Y h:i A', strtotime($user->created_at)) }}
                                            </p>
                                        </div> --}}
                                        @if(isset($user->dating->avatar))
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('assets/frontend/images/users/' . $user->id . '/' . Str::of($user->dating->avatar)->replace(' ', '%20')) }}"
                                                    alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}" class="user-avatar">
                                            </a>
                                        @else
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('assets/frontend/images/user.png') }}"
                                                    alt="{{ $user->first_name . ' ' . $user->last_name ?? '' }}" class="user-avatar">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="event-right-box d-flex flex-column justify-content-center">
                                        <div class="event-details">
                                            <h5>{{ Str::of($user->first_name.' '.$user->last_name)->ucfirst() }}</h5>
                                            <p class="mb-0 detail">Gender: {{ Str::of($user->dating->gender)->ucfirst() ?? '' }}</p>
                                            <p class="mb-0 detail">Relationship: {{ Str::of($user->dating->relationship_status)->ucfirst() ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="event-comments">
                                        <a href="javascript:void(0);"><i class="fa-solid fa-message"></i></a>
                                    </div>
                                    <div class="event-share">
                                        <a href="javascript:void(0);"><i class="fa-solid fa-user-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-12 mb-4 text-center">
                                <h5><a href="javascript:void(0);">No Records Found!</a></h5>
                            </div>
                        @endif
                    @empty
                        <div class="col-lg-12 mb-4 text-center">
                            <h5><a href="javascript:void(0);">No Records Found!</a></h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
