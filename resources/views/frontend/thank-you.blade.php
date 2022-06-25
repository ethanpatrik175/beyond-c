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
                            <h1 class="text-white">{{ Session::get('type') }}</h1>
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
