@extends('layouts.frontend.master')

@section('title')
    {{ 'Login' }}
@endsection

@section('content')
    <div class="main-container">
        <x-mobile-view-slide />
        <section class="home-banner">
            <div class="container center-container">
                <div class="row align-items-center">
                    <div class="col-lg-5 offset-lg-1">
                        <div class="section-heading">
                            <h5>Welcome To Engaging Singles</h5>
                            <h1>Are You Waiting For <span>Dating?</span></h1>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s</p>
                        </div>
                        <div class="links mt-4">
                            <button><a href="javascript:void(0);" onclick="popUpDisplay()">Find Your Date</a></button>
                            <button><a href="{{ route('front.view.events') }}">Book Event Ticket</a></button>
                        </div>
                        <div class="count-up d-flex justify-content-center justify-content-md-start mt-4">
                            <div class="left-count text-center">
                                <h4>10M+</h4>
                                <p>Active Datings</p>
                            </div>
                            <div class="right-count text-center">
                                <h4>150M+</h4>
                                <p>Events Booking</p>
                            </div>
                        </div>
                    </div>
                    <div class="offset-lg-1 d-none d-lg-block col-lg-4">
                        <div class="form-div">
                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                @csrf
                                <div class="section-heading">
                                    {{-- <h5>Join Our</h5> --}}
                                    <h4>Login</h4>
                                    <p>Special Offers For Join With Us</p>
                                    @if (Session::has('message'))
                                        <p>{{ __(Session::get('message')) }}</p>
                                    @endif
                                    @if ($errors->any())
                                        <div class="col-sm-12">
                                            @foreach ($errors->all() as $error)
                                                <p>{{ __($error) }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group text-start">
                                    <input type="email" name="email" placeholder="Email Address"
                                        class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required
                                        autocomplete="email" autofocus />
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                                <div class="form-group text-start">
                                    <input type="password" name="password"
                                        class="@error('password') is-invalid @enderror mt-3" value="{{ old('email') }}"
                                        required placeholder="Password" />
                                    <div class="invalid-feedback">
                                        Please enter a valid password.
                                    </div>
                                </div>
                                <button class="mt-3 text-white" type="submit">LOGIN</button>
                                <div class="forgot-pass mt-4">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                            @endif
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
                                    Cookies Policy.</p>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush
