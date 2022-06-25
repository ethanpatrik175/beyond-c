@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        a.red-label {
            color: rgb(192, 0, 0);
        }

        a.red-label:hover {
            color: #c00000;
        }

        .page-item.active .page-link {
            background-color: #c00000;
            border-color: #c00000;
        }

        .page-link:hover,
        .page-link {
            color: #c00000;
        }
    </style>
@endpush

@section('content')
    <div class="main-container events-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="events py-5">
            <div class="container">
                <div class="row bordered-row justify-content-md-around">
                    <div class="col-lg-7 d-lg-flex">
                        <div class="event-in">
                            <label for="events">Events In</label>
                            <input type="text" name="events" id="events" placeholder="5/2022">
                        </div>
                        {{-- <div class="events-search mt-3 mt-lg-0">
                            <label for="search">Search</label>
                            <input type="text" name="events" id="events" placeholder="Keyword">
                        </div> --}}
                        <div class="links red mt-3 mt-lg-0">
                            <button><a href="#">Find Events</a></button>
                        </div>
                    </div>
                    <div class="col-lg-4 d-lg-flex justify-content-end">

                        {{-- <div class="links white mt-3 mt-lg-0">
                            <button><a href="#">Post New Events</a></button>
                        </div> --}}
                        <div class="events-menu-icons text-center mt-3 mt-lg-0">

                            <a href="#"><img src="{{ asset('assets/frontend/images/calendar.svg') }}" alt=""></a>
                            <a href="#"><img src="{{ asset('assets/frontend/images/grid.svg') }}" alt=""></a>
                            <a href="#"><img src="{{ asset('assets/frontend/images/list.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="events-archive pb-5 pt-5 pt-lg-0">
            <div class="container">
                <div class="row">
                    @forelse ($events as $event)
                        <div class="col-lg-12 mb-4">
                            <div class="events-box d-lg-flex position-relative">
                                <div class="event-left-box position-relative">
                                    <div class="event-date">
                                        <p class="m-0 text-white">{{ date('d-M-Y h:i A', strtotime($event->start_at)) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('front.event.detail', $event->slug) }}">
                                        <img src="{{ asset('assets/frontend/images/events/' . Str::of($event->image)->replace(' ', '%20')) }}"
                                            alt="{{ $event->slug ?? '' }}">
                                    </a>
                                </div>
                                <div class="event-right-box d-flex flex-column justify-content-center">
                                    <div class="event-details">
                                        <a href="{{ route('front.event.detail', $event->slug) }}">
                                            <h5>{{ Str::limit($event->title, 70) }}</h5>
                                        </a>
                                        <p>{{ json_decode(@$event->summary) }}</p>
                                    </div>
                                    <div class="event-date-author">
                                        <p>by <span
                                                class="text-white">{{ @$event->addedBy->first_name . ' ' . @$event->addedBy->last_name }}
                                            </span> - {{ date('M d, Y', strtotime($event->created_at)) }}</p>
                                        <p class="mt-2">
                                            <del class="text-danger">${{ json_decode(@$event->metadata)->orig_price }}</del> &nbsp;${{ json_decode(@$event->metadata)->price }} <br /><span> {{-- <a href="{{ route('book.ticket') }}">Book Ticket Now</a> --}} </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="event-comments">
                                    <p><i class="fa-solid fa-message"></i> 0</p>
                                </div>
                                <div class="event-share">
                                    <a href="javascript:void(0);"><i class="fa-solid fa-share"></i></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12 mb-4 text-center">
                            <h5><a href="javascript:void(0);">No Event Found!</a></h5>
                        </div>
                    @endforelse

                    <div class="col-sm-12 text-center">
                        {{-- {!! $events->links() !!} --}}
                        {!! $events->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
