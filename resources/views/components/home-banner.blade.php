<section class="home-banner">
    <div class="container center-container @auth {{ 'text-center' }} @endauth">
        <div class="row align-items-center">
            <div class="@auth {{ 'col-lg-12' }} @else {{ 'col-lg-5 offset-lg-1' }} @endauth">
                <div class="section-heading">
                    <h5>{{ (isset($bannerTitle)) ? json_decode($bannerTitle->headings)->one : '' }}</h5>
                    <h1>{!! (isset($bannerTitle)) ? json_decode($bannerTitle->headings)->two  : '' !!}</h1>
                    <p>{{ (isset($bannerTitle)) ? $bannerTitle->description : '' }}</p>
                </div>
                <div class="links mt-4">
                    <button><a href="{{ route('find.your.date') }}">{{(isset($bannerTitle)) ? json_decode($bannerTitle->buttons)->title1 : ''}}</a></button>
                    <button><a href="{{ route('front.view.events') }}">{{(isset($bannerTitle)) ? json_decode($bannerTitle->buttons)->title2 : ''}}</a></button>
                </div>
                <div class="count-up d-flex justify-content-center @auth {{ 'justify-content-md-center' }} @else {{ 'justify-content-md-start' }} @endauth mt-4">
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
            @auth
            @else
                <div class="offset-lg-1 d-none d-lg-block col-lg-4">
                    <div class="form-div p-3">
                        @if (Route::is('login'))
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
                                                <a
                                                    href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
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
                        @else
                            <form method="POST" action="{{ route('register') }}"
                                class="needs-validation custom-validation" novalidate>
                                @csrf
                                <div class="section-heading">
                                    <h5>Join Our</h5>
                                    <h4>Membership</h4>
                                    <p>Special Offers For Join With Us</p>
                                </div>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="form-group text-start">
                                            <label for="first_name" class="black-text mb-1">First Name</label>
                                            <input type="text" id="first_name" name="first_name"
                                                data-parsley-required-message="Please enter first name"
                                                placeholder="First Name" value="{{ old('first_name') }}" required
                                                autofocus />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group text-start">
                                            <label for="last_name" class="black-text mb-1">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" placeholder="Last Name"
                                                value="{{ old('last_name') }}" />
                                            <div class="invalid-feedback">
                                                Please enter your last name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group text-start">
                                            <label for="email" class="black-text mb-1">Email</label>
                                            <input type="email" id="email" name="email"
                                                data-parsley-required-message="Enter valid email address"
                                                placeholder="Email" value="{{ old('email') }}" required />
                                            <div class="invalid-feedback">
                                                Please enter your email
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-3">
                                        <div class="form-group text-start">
                                            <label for="password" class="black-text mb-1">Password</label>
                                            <input type="password" id="password" name="password"
                                                data-parsley-required-message="Enter Password" placeholder="Password"
                                                required />
                                            <div class="invalid-feedback">
                                                Please enter password.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-3">
                                        <div class="form-group text-start">
                                            <label for="confirm_password" class="black-text mb-1">Confirm Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password"
                                                placeholder="Confirm Password"
                                                data-parsley-required-message="Enter valid confirm password"
                                                data-parsley-equalto="#password" data-parsley-trigger="keyup" required />
                                            <div class="invalid-feedback">
                                                Please retype password to confirm.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="red-button">Sign Up Now</button>
                                    <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
                                        Cookies Policy.</p>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endauth

        </div>
    </div>
</section>
