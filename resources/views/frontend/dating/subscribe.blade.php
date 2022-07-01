@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        a.red-label {
            color: rgb(192, 0, 0);
        }

        a.red-label:hover {
            color: #c00000;
        }

        .page-item.active .page-link {
            background-color: #c00000;
            border-color: #c00000;
        }

        .page-link:hover,
        .page-link {
            color: #c00000;
        }
    </style>
@endpush

@section('content')
    <div class="main-container events-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="events py-5">
            <div class="container">
                <div class="row bordered-row justify-content-md-around">
                    <div class="col-lg-7 d-lg-flex">
                        <div class="event-in">
                            <label for="events">Events In</label>
                            <input type="text" name="events" id="events" placeholder="5/2022">
                        </div>
                        {{-- <div class="events-search mt-3 mt-lg-0">
                            <label for="search">Search</label>
                            <input type="text" name="events" id="events" placeholder="Keyword">
                        </div> --}}
                        <div class="links red mt-3 mt-lg-0">
                            <button><a href="#">Find Events</a></button>
                        </div>
                    </div>
                    <div class="col-lg-4 d-lg-flex justify-content-end">

                        {{-- <div class="links white mt-3 mt-lg-0">
                            <button><a href="#">Post New Events</a></button>
                        </div> --}}
                        <div class="events-menu-icons text-center mt-3 mt-lg-0">

                            <a href="#"><img src="{{ asset('assets/frontend/images/calendar.svg') }}"
                                    alt=""></a>
                            <a href="#"><img src="{{ asset('assets/frontend/images/grid.svg') }}"
                                    alt=""></a>
                            <a href="#"><img src="{{ asset('assets/frontend/images/list.svg') }}"
                                    alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="events-archive pb-5 pt-5 pt-lg-0">
            <div class="container">
                <div class="row">
                    @forelse ($packages as $package)
                        <div class="col-lg-12 mb-4">
                            <div class="events-box d-lg-flex position-relative">
                                <div class="event-right-box d-flex flex-column justify-content-center p-5">
                                    <div class="event-details">
                                        <h5>{{ $package->name }}</h5>
                                        <p>{!! isset($package->description) ? json_decode($package->description) : '' !!}</p>
                                    </div>
                                    <div class="event-date-author">
                                        <p class="mt-2">
                                            @if ($package->charge_type == 'monthly')
                                                @if ( ($package->discount_per_month > 0) && ($package->discount_per_month < $package->price_per_month) )
                                                    <del class="text-danger">${{ number_format($package->price_per_month, 2) }}</del> &nbsp;${{ number_format($package->discount_per_month, 2) }} / {{ Str::of($package->charge_type)->upper() }} <br /><span></span>
                                                @else
                                                    ${{ number_format($package->price_per_month, 2) }} / {{ Str::of($package->charge_type)->upper() }}
                                                @endif                                                
                                            @else
                                                @if ( ($package->discount_per_year > 0) && ($package->discount_per_year < $package->price_per_month) )
                                                    <del class="text-danger">${{ number_format($package->price_per_year, 2) }}</del> &nbsp;${{ number_format($package->discount_per_year, 2) }} / {{ Str::of($package->charge_type)->upper() }} <br /><span></span>
                                                @else
                                                    ${{ number_format($package->price_per_year, 2) }} / {{ Str::of($package->charge_type)->upper() }}
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                {{-- <div class="event-comments">
                                    <p><i class="fa-solid fa-message"></i> 0</p>
                                </div>
                                <div class="event-share">
                                    <a href="javascript:void(0);"><i class="fa-solid fa-share"></i></a>
                                </div> --}}
                            </div>
                        </div>
                    @empty
                    <div class="col-lg-12 mb-4 text-center">
                        <h5><a href="javascript:void(0);">No Package Found!</a></h5>
                    </div>
                    @endforelse

                    <div class="col-sm-12 text-center">
                        {{-- {!! $events->links() !!} --}}
                        {{-- {!! $packages->links('pagination::bootstrap-4') !!} --}}
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
