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
        <x-banner banner-title="Volunteer Sign Up" />
        <section class="volunteer-signup py-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        @if (Session::has('message'))
                            <p class="text-danger">{{ __(Session::get('message')) }}</p>
                        @endif
                        @if ($errors->any())
                            <div class="col-sm-12">
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger">{{ __($error) }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('register') }}" class="needs-validation custom-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    data-parsley-required-message="Please enter first name" placeholder="First Name"
                                    value="{{old('first_name')}}"
                                    required autofocus />
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" placeholder="Last Name" value="{{old('last_name')}}" />
                                <div class="invalid-feedback">
                                    Please enter your last name.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                    data-parsley-required-message="Enter valid email address" placeholder="Email" value="{{old('email')}}"
                                    required />
                                <div class="invalid-feedback">
                                    Please enter your email
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password"
                                    data-parsley-required-message="Enter Password" placeholder="Password" required />
                                <div class="invalid-feedback">
                                    Please enter password.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    placeholder="Confirm Password"
                                    data-parsley-required-message="Enter valid confirm password"
                                    data-parsley-equalto="#password" data-parsley-trigger="keyup" required />
                                <div class="invalid-feedback">
                                    Please retype password to confirm.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="terms mt-3">
                                <input type="checkbox" name="agree" value="1" id="agree" {{ old('agree') == 1 ? 'checked' : '' }} />
                                <label for="agree">Agree Terms & Conditions</label>
                            </div>
                        </div>
                    </div>
                    <div class="links mt-4 text-center">
                        <button type="submit" class="red-button">Sign Up Now</button>
                    </div>
                </form>
            </div>
        </section>
        <x-footer />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush
