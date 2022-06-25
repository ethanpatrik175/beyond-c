@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container travel-detail-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="travel-details section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-lg-center">
                        <div class="section-heading">
                            <h1 class="text-white">Solo Package</h1>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 align-items-center">
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
                                <h4 class="mb-3">Tour to London</h4>
                                <p><i class="fa-solid fa-location-dot"></i>York</p>
                            </div>
                            <div class="package-duration d-lg-flex">
                                <p> <i class="fa-solid fa-clock"></i> 10 days</p>
                                <p><i class="fa-solid fa-users"></i> From 1 to 22 people</p>
                            </div>
                            <div class="package-requirements mt-2">
                                <h6>Requirements</h6>
                                <ul class="mt-4">
                                    <li>Passport</li>
                                    <li>COVID-19 Vaccination card</li>
                                    <li>Face mask</li>
                                </ul>
                            </div>
                            <div class="package-events d-flex mt-4">
                                <p>Sightseeing</p>
                                <p>Multi Day Tour</p>
                                <p>Road Trip</p>
                            </div>
                        </div>
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
