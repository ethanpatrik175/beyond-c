@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container contact-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="contact-us section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading text-width text-lg-center">
                            <h1 class="text-white">We Are Always Here For You!</h1>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit explicabo laborum hic! </p>
                        </div>
                    </div>
                </div>
                <form>
                    <div class="row mt-md-5 mt-2">
                        <div class="col-lg-6">
                            <input type="text" placeholder="Your Name*">
                            <input type="text" placeholder="Your Email*" class="mt-4">
                        </div>
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <input type="text" placeholder="Last Name*">
                            <select class="mt-4">
                                <option value="" disabled selected>Inquiry Select</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <textarea name="" id="" cols="30" rows="10" placeholder="Your Message"></textarea>
                        </div>
                    </div>
                    <div class="links mt-4 text-center">
                        <button type="submit">Submit Now</button>
                    </div>
                </form>
            </div>
        </section>
        <section class="map">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 px-0">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387190.27988647175!2d-74.25986673512958!3d40.697670068477386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1653423760372!5m2!1sen!2s"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
