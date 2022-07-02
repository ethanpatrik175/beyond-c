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

                    <div class="col-lg-12 mb-5">
                        <div class="events-box d-lg-flex position-relative">
                            <div class="event-right-box d-flex flex-column justify-content-center p-5">
                                <div class="event-details">
                                    <h5>Name Here</h5>
                                    <p>Description here</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
