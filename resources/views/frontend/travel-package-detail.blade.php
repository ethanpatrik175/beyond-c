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
                            <h1 class="text-white">{{$travel_package->travel_type->name}}</h1>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 align-items-center">
                    <div class="col-lg-7">
                        <div class="owl-carousel travel-carousel owl-theme">
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/travelpackages/' . $travel_package->image) }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/travelpackages/' . $travel_package->image) }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/travelpackages/' . $travel_package->image) }}">
                                </div>
                            </div>
                            <div class="item">
                                <div class="img-div">
                                    <img src="{{ asset('assets/frontend/images/travelpackages/' . $travel_package->image) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="package-details">
                            <div class="package-location">
                                <h4 class="mb-3">Tour to {{$travel_package->location}}</h4>
                                <p><i class="fa-solid fa-location-dot"></i>{{$travel_package->location}}</p>
                            </div>
                            <div class="package-duration d-lg-flex">
                                <p> <i class="fa-solid fa-clock"></i> {{$travel_package->no_of_days}} days</p>
                                <p><i class="fa-solid fa-users"></i> {{$travel_package->no_of_members}} people</p>
                            </div>
                            <div class="package-requirements mt-2">
                                <h6>Requirements</h6>
                                <?php
                                // dd($travel_package->requirements)
                                ?>
                                <div class="mt-4" style="color: white">
                                    {!!$travel_package->requirements!!}
                                </div>
                            </div>
                            <div class="package-events d-flex mt-4">
                                @forelse ($tags as $tags )
                                <p>{{$tags->title}}</p>
                                @empty
                                 <p>Not found</p> 
                                @endforelse
                               
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
