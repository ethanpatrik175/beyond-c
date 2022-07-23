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
                        <a href="{{ url('chat/') }}" class="d-none d-md-block">
                            <i class="fa fa-comments-o fa-2x" aria-hidden="true"></i>
                        </a>
                        <a href="javascript:void(0);" class="d-none d-md-block">
                            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
                            <span class="btn__badge pulse-button ">4</span>
                        </a>
                        
                        @if (isset(Auth::user()->dating->avatar))
                            <div class="btn-group d-none d-md-block">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="{{ asset('assets/frontend/images/users/' . Auth::user()->id . '/' . Auth::user()->dating->avatar) }}"
                                        alt="" class="rounded-circle inline-block" width="40" height="40" />
                                    {{ Auth::user()->name ?? '' }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item text-dark"
                                            href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off font-size-16 align-middle me-1 text-danger"></i><span
                                                key="t-logout">{{ __('Logout') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <img src="{{ asset('assets/backend/images/users/user.png') }}" alt=""
                                class="d-none d-md-block rounded-circle" width="40" height="40" />
                        @endif
                    @endauth
                    <div class="mob-menu d-flex d-md-none"> <button> <i class="fa-solid fa-bars"></i> </button> </div>
                </div>
                <div class="col-lg-3 col-md-2 d-none  d-md-flex align-items-center justify-content-center">
                    @guest()
                        <a href="{{ route('login') }}">Login &VerticalLine;</a> &nbsp;<a
                            href="{{ route('register') }}">Sign Up</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
