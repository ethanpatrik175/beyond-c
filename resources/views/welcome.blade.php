@extends('layouts.frontend.master')

@push('styles')
    <style>
        .parsley-required,
        .parsley-equalto {
            color: red;
            font-weight: bold !important;
        }

        .red-button{
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
    <div class="main-container">
        <x-mobile-view-slide />
        <x-home-banner />

        <section class="how-it-work position-relative section-padding">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="section-heading">
                            
                            <h5>{{$content->sectioncontent[0]->content}}</h5>
                            <h1 class="text-white">{{$content->sectioncontent[1]->content}}</h1>
                            <p class="mt-lg-4">{{$content->sectioncontent[2]->content}}</p>
                        </div>
                        <div class="links mt-4">
                            <button><a href="#">{{$content->sectioncontent[3]->content}}</a></button>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row mt-4 mt-lg-0">
                            <div class="col-lg-7 offset-lg-5">
                                <div class="work-box text-center">
                                    <img src="{{ asset('assets/frontend/images/Profile.svg') }}" alt="">
                                    <h6 class="mt-3">{{$section_2->sectioncontent[0]->content}}</h6>
                                    <p class="mt-2">{{$section_2->sectioncontent[1]->content}}</p>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="work-box text-center">
                                    <img src="{{ asset('assets/frontend/images/Map.svg') }}" alt="">
                                    <h6 class="mt-3 text-white">{{$section_3->sectioncontent[0]->content}}</h6>
                                    <p class="mt-2 text-white">{{$section_3->sectioncontent[0]->content}}</p>
                                </div>
                            </div>
                            <div class="col-lg-7 offset-lg-4">
                                <div class="work-box text-center">
                                    <img src="{{ asset('assets/frontend/images/Love.svg') }}" alt="">
                                    <h6 class="mt-3 text-white">{{$section_4->sectioncontent[0]->content}}</h6>
                                    <p class="mt-2">{{$section_4->sectioncontent[0]->content}}</p>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section class="why-choose-us">
            <div class="container-fluid">
                <div class="row align-items-end">
                    <div class="col-lg-6 ps-lg-0">
                        <div class="img-div">
                            <img src="{{ asset('assets/frontend/images/Group_54.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="section-heading">
                            <h5>{{$heading_2->sectioncontent[0]->content}}</h5>
                            <h1 class="text-white">{{$heading_2->sectioncontent[1]->content}}</h1>
                        </div>
                        <div class="features mt-4">
                            <div class="feature-box d-flex">
                                <span>01</span>
                                <p>{{$heading_2->sectioncontent[2]->content}}</p>
                            </div>
                            <div class="feature-box d-flex mt-2">
                                <span>02</span>
                                <p>{{$heading_2->sectioncontent[3]->content}}</p>
                            </div>
                            <div class="feature-box d-flex mt-2">
                                <span>03</span>
                                <p>{{$heading_2->sectioncontent[4]->content}}</p>
                            </div>
                        </div>
                        <div class="links mt-3 mt-md-4">
                            <button><a href="#">Learn More</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
                            // dd($testimonal_1->sectioncontent[0]->content)    
                            ?>
        <x-testimonial :title="$testimonal_1"/>
        <x-footer />

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush