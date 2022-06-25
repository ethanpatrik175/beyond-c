@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container travel-detail-page event-details-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="travel-details section-padding">
            <div class="container">

                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="owl-carousel travel-carousel owl-theme">
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/beyond-10.jpg') }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/beyond-14.jpg') }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/beyond-22.jpg') }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/beyond-17.jpg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="package-details">
                            <div class="package-location">
                                <h4 class="mb-1">{{ $event->title ?? '' }}</h4>
                                @if (isset($event->sub_title))
                                    <p><i>{{ $event->sub_title }}</i></p>
                                @endif
                                <p>{{ json_decode(@$event->summary) }}</p>
                            </div>
                            <div class="package-duration d-lg-flex">
                                <p>
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $event->venue->title ?? '' }} {{ $event->venue->sub_title }}
                                    ({{ json_decode(@$event->venue->address) ?? '' }} {{ $event->venue->city ?? '' }}
                                    {{ $event->venue->state ?? '' }} {{ $event->venue->country ?? '' }})
                                </p>
                            </div>
                            <div class="package-duration d-lg-flex">
                                <p> <i class="fa-solid fa-clock"></i>{{ date('d F Y', strtotime($event->start_at)) }}</p>
                            </div>
                            <div class="book-button d-flex align-items-center  mt-3">
                                {{-- <div class="num-inc-dec">
                                    <div class="value-button" id="decrease" onclick="decreaseValue()"
                                        value="Decrease Value">-</div>
                                    <input type="number" id="repeat_times" name="repeat_times" min="1" max="10" value="1">
                                    <div class="value-button" id="increase" onclick="increaseValue()"
                                        value="Increase Value">+</div>
                                </div> --}}
                                <a class="btn btn-danger" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('event-form').submit();">Book Ticket Now</a>
                                <form id="event-form" action="{{ route('book.ticket') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$event->id}}" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-4 bg-white">
                        {!! json_decode(@$event->description) !!}
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
@push('scripts')
    <script>
        $('.travel-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 2000,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })
    </script>
@endpush
