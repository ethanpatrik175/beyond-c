<section class="mob-menu-slide py-5">
    <div class="container">
        @auth
            <div class="row text-center mt-4">
                <div class="col-12">
                    <img src="{{ asset('assets/frontend/images/email-outline-badged.svg') }}" alt="">
                    <img src="{{ asset('assets/frontend/images/ic-1.svg') }}" alt="">
                    <img src="{{ asset('assets/frontend/images/notif-1.svg') }}" alt="">
                </div>
            </div>
        @else
            <div class="row text-center mt-4">
                <div class="col-12">
                    <div class="links mt-4">
                        <button class="red-btn"><a href="{{route('login')}}">LogIn</a></button>
                    </div>
                    <div class="links mt-4">
                        <button><a href="{{route('register')}}">Sign Up</a></button>
                    </div>
                </div>
            </div>
        @endauth        
    </div>
</section>
