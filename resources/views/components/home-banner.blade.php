<section class="home-banner">
    <div class="container center-container @auth {{'text-center'}} @endauth">
        <div class="row align-items-center">
            <div class="@auth {{'col-lg-12'}} @else {{'col-lg-5 offset-lg-1'}} @endauth">
                <div class="section-heading">
                    <h5>Welcome To Engaging Singles</h5>
                    <h1>Are You Waiting For <span>Dating?</span></h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br /> Lorem Ipsum
                        has been the industry's standard dummy text ever since the 1500s</p>
                </div>
                <div class="links mt-4">
                    <button><a href="javascript:void(0);" onclick="popUpDisplay()">Find Your Date</a></button>
                    <button><a href="{{ route('front.view.events') }}">Book Event Ticket</a></button>
                </div>
                <div class="count-up d-flex justify-content-center @auth {{'justify-content-md-center'}} @else {{'justify-content-md-start'}} @endauth mt-4">
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
                        <form method="POST" action="{{ route('register') }}" class="needs-validation custom-validation" novalidate>
                            @csrf
                            <div class="section-heading">
                                <h5>Join Our</h5>
                                <h4>Membership</h4>
                                <p>Special Offers For Join With Us</p>
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    <div class="form-group text-start">
                                        <label for="first_name">First Name</label>
                                        <input type="text" id="first_name" name="first_name"
                                            data-parsley-required-message="Please enter first name" placeholder="First Name"
                                            value="{{old('first_name')}}"
                                            required autofocus />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group text-start">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" value="{{old('last_name')}}" />
                                        <div class="invalid-feedback">
                                            Please enter your last name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text-start">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email"
                                            data-parsley-required-message="Enter valid email address" placeholder="Email" value="{{old('email')}}"
                                            required />
                                        <div class="invalid-feedback">
                                            Please enter your email
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group text-start">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password"
                                            data-parsley-required-message="Enter Password" placeholder="Password" required />
                                        <div class="invalid-feedback">
                                            Please enter password.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group text-start">
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
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="red-button">Sign Up Now</button>
                                <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy &
                                    Cookies Policy.</p>
                            </div>
                        </form>
                        
                    </div>
                </div>
            @endauth

        </div>
    </div>
</section>
