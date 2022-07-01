@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection
@push('styles')
    <style>
        .page-item.active .page-link {
            background-color: #c00000;
            border-color: #c00000;
        }
        .page-link:hover, .page-link{
            color: #c00000;
        }
    </style>
@endpush
@section('content')
    <div class="main-container travel-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="blogs travel py-5">
            <div class="container">
                <div class="row">
                    @forelse ($travel_package as $travel_packages)
                    <div class="col-lg-3">
                        <div class="blog-card text-center">
                            <div class="blog-card-upper">
                                <div class="img-div">
                                    <a href="{{route('front.travel.package.detail', $travel_packages->slug)}}">
                                        <img src="{{ asset('assets/frontend/images/travelpackages/' . $travel_packages->image) }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="blog-card-lower">
                                <div class="blog-category">
                                    <p class="text-white">{{$travel_packages->travel_type->name}}</p>
                                </div>
                                <div class="blog-desc pb-2">
                                    <h6 class="text-white">{{Str::words($travel_packages->name,4)}}</h6>
                                    <p>{!!Str::words($travel_packages->description ,10)!!}</p>
                                </div>
                                <div class="blog-comments text-center pt-3 mt-2">
                                    <h2>${{$travel_packages->price}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        
                    @endforelse
                   
                </div>
               
                <div class="col-sm-12 text-center mt-4">
                    {{-- {!! $events->links() !!} --}}
                    {!! $travel_package->links() !!}
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
