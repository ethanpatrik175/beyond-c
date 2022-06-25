<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" content="{{ csrf_token() }}" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/frontend/images/logos/favicon.ico')}}">
    <title>
        @section('title')
            {{ 'Welcome' }}
        @show
        -
        {{ config('app.name') }}
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/OwlCarousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    @routes
    <style>
        /* svg.w-5.h-5 {
            width: 25px;
            margin-top: 20px;
            margin-bottom: 20px;
            color: red;
        }
        .text-gray-700{
            color: red;
        } */
    </style>
    @stack('styles')
</head>

<body>

    <header class="header">
        <x-top-bar />
        <x-side-bar />
    </header>
    {{-- <div class="black-bg"></div>
    <div class="form-div form-popup">
        <form>
            <div class="section-heading">
                <h5>Join Our</h5>
                <h4>Membership</h4>
                <p>Special Offers For Join With Us</p>
            </div>
            <input type="text" placeholder="Name">
            <input type="email" class="mt-3" placeholder="Email">
            <button class="mt-3">
                <a href="#">Submit Now</a>
            </button>
            <p class="black-text mt-4">*By Subscription To Our Terms & Condition And Privacy & Cookies Policy.</p>
        </form>
    </div> --}}

    @yield('content')

    <script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/OwlCarousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers:{
                "X-CSRF-TOKEN": $('#csrf-token').attr('content')
            }
        });
    </script>
    <script>
        $('.testimonial-carousel').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            stagePadding: 450,
            center: true,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 10,
                },
                600: {
                    items: 3,
                    stagePadding: 10
                },
                1000: {
                    items: 1
                }
            }
        });

        $(".mob-menu button").on('click', function() {
            $(".mob-menu-slide").toggleClass("show")
        })

        $(document).ready(function() {
            setTimeout(popUpDisplay, 2000)
        })

        function popUpDisplay() {
            $(".form-popup").addClass("showThis");
            $(".black-bg").addClass("showThis");
        }


        $(document).on("click", ".black-bg", function() {
            popUpHide();
        })

        function popUpHide() {
            $(".form-popup").removeClass("showThis");
            $(".black-bg").removeClass("showThis");
        }
    </script>
    @stack('scripts')
</body>

</html>
