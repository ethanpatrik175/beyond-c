@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
<x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="contact-us section-padding products">
        
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="single-detail-filter-area">
                                <select name="category" id="category">
                                    <option value="" selected hidden>Select Category</option>
                                    <option value="1">Ocean View</option>
                                    <option value="2">City View</option>
                                    <option value="3">Presidential Suite</option>
                                </select>
                                <div class="multi-range-detail">
                                    <span>Price</span>
                                    <span>
                                        $<span id="lower1-val">0</span> - $<span id="upper1-val">300000</span>
                                    </span>
                                </div>
                                <div class="multi-range">
                                    <input type="range" min="0" max="300000" value="0" id="lower1" name="min_price">
                                    <input type="range" min="0" max="300000" value="300000" id="upper1" name="max_price">
                                </div>
                                <div class="links mt-4">
                                    <button>
                                        <a href="#">Filter</a>
                                    </button>
                                </div>
                            </div> 
                    </div>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="img-div">
                                    <img src="images/beyond-22.jpg">
                                </div>
                                <h5 class="prod-title">Lorem Ipsum Dolor</h5>
                                <p class="prod-price">$80.20</p>
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
     <script src="js/jquery-3.6.0.min.js"></script>
     
     <script>
         
        var lowerSlider1 = document.querySelector('#lower1'),
            upperSlider1 = document.querySelector('#upper1'),
            lowerVal1 = parseInt(lowerSlider1.value);
            upperVal1 = parseInt(upperSlider1.value);

        upperSlider1.oninput = function() {
            lowerVal1 = parseInt(lowerSlider1.value);
            upperVal1 = parseInt(upperSlider1.value);

            if (upperVal1 < lowerVal1 + 4) {
                lowerSlider1.value = upperVal1 - 4;

                if (lowerVal1 == lowerSlider1.min) {
                    upperSlider1.value = 4;
                }
            }
        };


        lowerSlider1.oninput = function() {
            lowerVal1 = parseInt(lowerSlider1.value);
            upperVal1 = parseInt(upperSlider1.value);

            if (lowerVal1 > upperVal1 - 4) {
                upperSlider1.value = lowerVal1 + 4;

                if (upperVal1 == upperSlider1.max) {
                    lowerSlider1.value = parseInt(upperSlider1.max) - 4;
                }

            }
        };

        $("#lower1,#upper1").on('change', function () {

            $("#lower1-val").html(lowerVal1);
            $("#upper1-val").html(upperVal1);
        });

    
     </script>
    @endpush