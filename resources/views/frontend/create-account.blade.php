@extends('layouts.frontend.master')

@section('title')
    {{ 'Register' }}
@endsection

@push('styles')
    <style>
        .parsley-required,
        .parsley-equalto {
            color: red;
        }
    </style>
@endpush

@section('content')
    <div class="main-container volunteer-signup-page">
        <x-mobile-view-slide />
        <x-create-account />
        <x-footer />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush