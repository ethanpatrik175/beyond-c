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
                            {{-- @dd($aboutus['heading_3']->sectioncontent[0]->content) --}}
                            
                            <h5>{{ isset($aboutus) ? (isset($aboutus['heading_1']) ? $aboutus['heading_1']->sectioncontent[0]->content : '') : '' }}</h5>
                            <h1 class="text-white">{{ isset($aboutus) ? (isset($aboutus['heading_2']) ? $aboutus['heading_2']->sectioncontent[0]->content : '') : '' }}</h1>
                            <p class="mt-lg-4">{{ isset($aboutus) ? (isset($aboutus['para_1']) ? $aboutus['para_1']->sectioncontent[0]->content : '') : '' }}</p>
                            <p>{{ isset($aboutus) ? (isset($aboutus['para_2']) ? $aboutus['para_2']->sectioncontent[0]->content : '') : '' }}</p>
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
                            <h5 class="text-white">{{ isset($aboutus) ? (isset($aboutus['heading_1']) ? $aboutus['heading_1']->sectioncontent[0]->content : '') : '' }}</h5>
                            <h1 class="text-white mt-3">{{ isset($aboutus) ? (isset($aboutus['heading_3']) ? $aboutus['heading_3']->sectioncontent[0]->content : '') : '' }}</h1>
                            <p class="mt-lg-3 text-white">{{ isset($aboutus) ? (isset($aboutus['para_3']) ? $aboutus['para_3']->sectioncontent[0]->content : '') : '' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="img-div">
                            <img src="{{ asset('assets/frontend/sectioncontent/' . @$aboutus['image']->sectioncontent[0]->content) ?? '' }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-testimonial :title="$testimonal_1"/>
        <x-footer />
    </div>
@endsection
