@extends('layouts.frontend.master')

@section('title')
    {{ 'Login' }}
@endsection

@push('styles')
    <style>
        .invalid-feedback{
            font-weight: bold !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-container">
        <x-mobile-view-slide />
        {{-- <x-home-banner :banner-title="$bannerTitle" /> --}}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush
