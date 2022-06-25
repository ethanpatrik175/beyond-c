@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container travel-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="blogs travel py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="blog-card text-center">
                            <div class="blog-card-upper">
                                <div class="img-div">
                                    <a href="{{route('front.travel.package.detail', 'test-travel-package')}}">
                                        <img src="{{asset('assets/frontend/images/beyond-10-mini.jpg')}}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="blog-card-lower">
                                <div class="blog-category">
                                    <p class="text-white">Solo Package</p>
                                </div>
                                <div class="blog-desc pb-2">
                                    <h6 class="text-white">Your Wellness Expert On Social Media - Why Is Online Grooming
                                        A Hot Trend?</h6>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto rerum sapiente
                                        maxime magni</p>
                                </div>
                                <div class="blog-comments text-center pt-3 mt-2">
                                    <h2>$100</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-4 mt-md-0">
                        <div class="blog-card text-center">
                            <div class="blog-card-upper">
                                <div class="img-div">
                                    <a href="{{route('front.travel.package.detail', 'test-travel-package')}}">
                                        <img src="{{asset('assets/frontend/images/beyond-14-mini.jpg')}}" alt="">
                                    </a>                                    
                                </div>
                            </div>
                            <div class="blog-card-lower">
                                <div class="blog-category">
                                    <p class="text-white">Group Package</p>
                                </div>
                                <div class="blog-desc pb-2">
                                    <h6 class="text-white">Your Wellness Expert On Social Media - Why Is Online Grooming
                                        A Hot Trend?</h6>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto rerum sapiente
                                        maxime magni</p>
                                </div>
                                <div class="blog-comments text-center pt-3 mt-2">
                                    <h2>$1000</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-4 mt-md-0">
                        <div class="blog-card text-center">
                            <div class="blog-card-upper">
                                <div class="img-div">
                                    <a href="{{route('front.travel.package.detail', 'test-travel-package')}}">
                                        <img src="{{asset('assets/frontend/images/beyond-22-mini.jpg')}}" alt="">
                                    </a>                                    
                                </div>
                            </div>
                            <div class="blog-card-lower">
                                <div class="blog-category">
                                    <p class="text-white">Solo Package</p>
                                </div>
                                <div class="blog-desc pb-2">
                                    <h6 class="text-white">Your Wellness Expert On Social Media - Why Is Online Grooming
                                        A Hot Trend?</h6>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto rerum sapiente
                                        maxime magni</p>
                                </div>
                                <div class="blog-comments text-center pt-3 mt-2">
                                    <h2>$100</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-4 mt-md-0">
                        <div class="blog-card text-center">
                            <div class="blog-card-upper">
                                <div class="img-div">
                                    <a href="{{route('front.travel.package.detail', 'test-travel-package')}}">
                                        <img src="{{asset('assets/frontend/images/beyond-17-mini.jpg')}}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="blog-card-lower">
                                <div class="blog-category">
                                    <p class="text-white">Group Package</p>
                                </div>
                                <div class="blog-desc pb-2">
                                    <h6 class="text-white">Your Wellness Expert On Social Media - Why Is Online Grooming
                                        A Hot Trend?</h6>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto rerum sapiente
                                        maxime magni</p>
                                </div>
                                <div class="blog-comments text-center pt-3 mt-2">
                                    <h2>$1000</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
