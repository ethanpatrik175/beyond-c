<div class="top-menu-area d-flex">
    <div class="header-logo">
        <a href="{{ route('front.welcome') }}">
            <img src="{{ asset('assets/frontend/images/logo.png') }}" alt="logo">
        </a>
    </div>
    <div class="top-menu-items">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-10 position-relative d-flex">
                    <input type="search" placeholder="Search...">
                    <img src="{{ asset('assets/frontend/images/ic-2.svg') }}" alt="">
                </div>
                {{-- <div class="col-lg-1 col-md-1 d-none d-md-block">
                    <div class="img-div-round">
                        <img src="{{ asset('assets/frontend/images/ic-3.svg') }}" alt="">
                    </div>
                </div> --}}
                <div class="col-lg-3 col-md-3 col-2 d-md-flex justify-content-around">
                @auth()
                    <img src="{{ asset('assets/frontend/images/email-outline-badged.svg') }}" class="d-none d-md-block ml-3" alt="">
                    <img src="{{ asset('assets/frontend/images/ic-1.svg') }}" alt="" class="d-none d-md-block">
                    <img src="{{ asset('assets/frontend/images/notif-1.svg') }}" alt="" class="d-none d-md-block">
                @endauth
                    <div class="mob-menu d-flex d-md-none"> <button> <i class="fa-solid fa-bars"></i> </button> </div>
                </div>
                <div class="col-lg-3 col-md-2 d-none  d-md-flex align-items-center justify-content-center">
                    @auth()
                        <a href="{{route('user.dashboard')}}"><i class="fa-solid fa-bars"></i> Dashboard</a>
                    @else
                        <a href="{{route('login')}}">LogIn &VerticalLine;</a> &nbsp;<a href="{{route('register')}}">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
