@extends('layouts.frontend.master')

@section('title')
    {{ 'Login' }}
@endsection

@section('content')
    <div class="main-container">
        <x-mobile-view-slide />
        <x-home-banner />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush
