@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container about-page">
        <x-mobile-view-slide />
        <x-banner :banner-title="$bannerTitle" />

        <section class="about-us section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-lg-center">
                        <div class="section-heading">
                            <h5>Social Networking</h5>
                            <h1 class="text-white">Why Choose Engaging Singles</h1>
                            <p class="mt-lg-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti ab, quos
                                velit est omnis voluptate odit mollitia fugiat illum? Delectus illum temporibus aspernatur id
                                tempore quaerat quibusdam deleniti ea consectetur! Optio
                                qui odit quas quibusdam, natus doloremque et id a consequatur, blanditiis itaque commodi
                                voluptas mollitia vero omnis aliquid suscipit qui odit quas quibusdam, natus doloremque et id a
                                consequatur.</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti ab, quos velit est omnis
                                voluptate odit mollitia fugiat illum? Delectus illum temporibus aspernatur id tempore quaerat
                                quibusdam deleniti ea consectetur! Optio
                                qui odit quas quibusdam, natus doloremque et id a consequatur, blanditiis itaque commodi
                                voluptas mollitia vero omnis aliquid suscipit qui odit quas quibusdam, natus doloremque et id a
                                consequatur.</p>
                        </div>
                    </div>

                </div>
                <div class="row mt-lg-5">
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info4.png')}}" alt="">
                            <h6 class="text-white mt-3">Free To Use</h6>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info2.png')}}" alt="">
                            <h6 class="text-white mt-3">Multiple Groups To Join</h6>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info3.png')}}" alt="">
                            <h6 class="text-white mt-3">User-Friendly Social Media</h6>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info1.png')}}" alt="">
                            <h6 class="text-white mt-3">Opportunity To Meet New People</h6>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info5.png')}}" alt="">
                            <h6 class="text-white mt-3">Multiple Communication Tools</h6>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="about-box text-center">
                            <img src="{{asset('assets/frontend/images/info6.png')}}" alt="">
                            <h6 class="text-white mt-3">Networking For Healthcare Professionals</h6>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-3 d-flex justify-content-center">
                        <div class="count-up d-flex mt-4">
                            <div class="left-count text-center">
                                <h4>1502+</h4>
                                <p>Registered Members</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-center">
                        <div class="count-up d-flex mt-4">
                            <div class="left-count text-center">
                                <h4>861+</h4>
                                <p>Features Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-center">
                        <div class="count-up d-flex mt-4">
                            <div class="left-count text-center">
                                <h4>7412+</h4>
                                <p>Friend Groups</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-center">
                        <div class="count-up d-flex mt-4">
                            <div class="left-count text-center">
                                <h4>294+</h4>
                                <p>Favourite Pages</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="how-we-started position-relative section-padding">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="section-heading">
                            <h5 class="text-white">Social Network</h5>
                            <h1 class="text-white mt-3">Learn A Little More About Engaging Singles And How We Started</h1>
                            <p class="mt-lg-3 text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti ab,
                                quos velit est omnis voluptate odit mollitia fugiat illum? Delectus illum temporibus aspernatur
                                id tempore quaerat quibusdam deleniti ea consectetur! Optio</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="img-div">
                            <img src="{{asset('assets/frontend/images/beyond-8.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-testimonial />
        <x-footer />
    </div>
@endsection
