@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container events-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="events-archive pb-3 pt-5 pt-lg-0">
            <div class="container">

                <div class="row pt-5">

                    @if (Session::has('message'))
                        <div class="col-sm-12 mb-4">
                            <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show" role="alert">
                                @if (Session::get('type') == 'danger')
                                    <i class="mdi mdi-block-helper me-2"></i>
                                @else
                                    <i class="mdi mdi-check-all me-2"></i>
                                @endif
                                {{ __(Session::get('message')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="col-sm-12 mb-4">
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-block-helper me-2"></i>
                                    {{ __($error) }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @forelse ($packages as $package)
                        <div class="col-lg-12 mb-5">
                            <div class="events-box d-lg-flex position-relative">
                                <div class="event-right-box d-flex flex-column justify-content-center p-5">
                                    <div class="event-details">
                                        <h5>{{ $package->name }}</h5>
                                        <p>{!! isset($package->description) ? json_decode($package->description) : '' !!}</p>
                                    </div>
                                    <div class="event-date-author">
                                        <p class="mt-2">
                                            @if ($package->charge_type == 'monthly')
                                                @if ($package->discount_per_month > 0 && $package->discount_per_month < $package->price_per_month)
                                                    <del
                                                        class="text-danger">${{ number_format($package->price_per_month, 2) }}</del>
                                                    &nbsp;${{ number_format($package->discount_per_month, 2) }} /
                                                    {{ Str::of($package->charge_type)->upper() }} <br /><span></span>
                                                @else
                                                    ${{ number_format($package->price_per_month, 2) }} /
                                                    {{ Str::of($package->charge_type)->upper() }}
                                                @endif
                                            @else
                                                @if ($package->discount_per_year > 0 && $package->discount_per_year < $package->price_per_month)
                                                    <del
                                                        class="text-danger">${{ number_format($package->price_per_year, 2) }}</del>
                                                    &nbsp;${{ number_format($package->discount_per_year, 2) }} /
                                                    {{ Str::of($package->charge_type)->upper() }} <br /><span></span>
                                                @else
                                                    ${{ number_format($package->price_per_year, 2) }} /
                                                    {{ Str::of($package->charge_type)->upper() }}
                                                @endif
                                            @endif
                                        </p>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form method="POST" action="{{ route('dating.subscribe.process') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $package->id }}" />
                                                    <div class="links mt-2">
                                                        @if(isset($dating->subscription_id) && ($dating->subscription_id == $package->id))
                                                            <button type="submit" style="margin-left: 0px; background: #c00000; color: #fff;">SUBSCRIBED</button>
                                                        @elseif(isset($dating->subscription_id) && ($dating->subscription_id != $package->id))
                                                            <button type="submit" style="margin-left: 0px; background: #c00000; color: #fff;">UPGRADE NOW</button>
                                                        @else
                                                            <button type="submit" style="margin-left: 0px; background: #c00000; color: #fff;">SUBSCRIBE NOW</button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
