@extends('layouts.frontend.master')

@section('title')
{{ __($pageTitle) }}
@endsection

@section('content')
<div class="main-container blog-page checkout">
    <x-banner :banner-title="$bannerTitle"></x-banner>
    <section class="blogs section-padding">
        <div class="container">
            <form method="POST" action="{{ route('store') }}" enctype="multipart/form-data">
                <div class="row">
                    @csrf
                    <div class="col-lg-7">
                        <h3 class="mb-4">Billing Details</h3>
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" name="Firstname" id="first-name" required>
                        <label for="last-name" class="mt-3">Last Name</label>
                        <input type="text" name="lastname" id="last-name" required>
                        <label for="email" class="mt-3">Email</label>
                        <input type="text" name="email" id="email" required>
                        <label for="company-name" class="mt-3">Company Name</label>
                        <input type="text" name="company_name" id="company-name">
                        <label for="address" class="mt-3">Street Address</label>
                        <input type="text" name="address" id="address">
                        <!-- <input type="text" class="mt-3" id="address"> -->
                        <label for="city" class="mt-3">Town/City</label>
                        <input type="text" name="city" id="city">
                        <label for="city" class="mt-3">State</label>
                        <select>
                            <option>State</option>
                            <option>State</option>
                            <option>State</option>
                            <option>State</option>
                        </select>
                        <label for="zip" class="mt-3">Zip</label>
                        <input type="text" name="zip" id="zip">
                        <label for="phone" class="mt-3">Phone Number</label>
                        <input type="text" name="phone" id="phone">
                        <label for="notes" class="mt-3">Order Notes</label>
                        <textarea name="content"></textarea>
                    </div>
                    <div class="col-lg-5">
                        <div class="order-box">
                            <div class="box-upper">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h6>Product</h6>
                                    </div>
                                    <div class="col-lg-8">
                                        <h6>{{$product_total}}</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="box-upper-mid">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h6> Lorem ipsm x 1</h6>
                                    </div>
                                    <div class="col-lg-8">
                                        <h6>$20.52</h6>
                                    </div>
                                </div>
                            </div> -->
                            <div class="box-lower-mid">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h6>Total</h6>
                                    </div>
                                    <div class="col-lg-8">
                                        <h6>{{$total}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="box-lower-end">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h6>Shipping</h6>
                                    </div>
                                    <div class="col-lg-8">
                                        <h6>Enter Shipping Address to view your shipping address.</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="box-end">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit explicabo
                                            laborum hic!Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit
                                            explicabo laborum hic!</p>
                                        <div class="links">
                                            <button type="submit">Place Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <x-footer />
</div>
@endsection
