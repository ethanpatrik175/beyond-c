<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <title>@section('title') {{ 'Dashboard' }} @show | {{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Dashboard - {{config('app.name')}}" name="description" />
        <meta content="The Developers!" name="author" />
        <meta id="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/frontend/images/logos/favicon.ico')}}">

        <link href="{{ asset('assets/backend/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/backend/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
        <!-- Bootstrap Css -->
        <link href="{{asset('assets/backend/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('assets/backend/libs/sweetalert2/sweetalert2.min.css')}}" />
        <!-- App Css-->
        <link href="{{asset('assets/backend/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/backend/css/custom.css')}}">
      
         <link rel="stylesheet" href="farbtastic.css" type="text/css" />

        @routes
        <style>
            .logo-lg img {
                height: 60px;
            }
            .table-scroll{ overflow: auto;}
            .mh-400{
                max-height: 400px;
            }
        </style>
        @stack('styles')
    </head>

    <body data-sidebar="dark">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('backend.header')
            <!-- ========== Left Sidebar Start ========== -->
            @include('backend.left-sidebar')
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <!-- Start Page Content -->
                @yield('page-content')
                <!-- End Page-content -->
                <!-- end modal -->
                @include('backend.footer')
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">

                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>
                <div class="p-4">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch">
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->
        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/backend/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{ asset('assets/backend/libs/select2/js/select2.min.js') }}"></script>
        <!-- apexcharts -->
        <script src="{{asset('assets/backend/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- dashboard init -->
        <script src="{{asset('assets/backend/js/pages/dashboard.init.js')}}"></script>

        <!-- form advanced init -->
        <script src="{{ asset('assets/backend/js/pages/form-advanced.init.js') }}"></script>
        <!-- loading overlay -->
        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
        <script>
            $.ajaxSetup({
                headers:{
                    "X-CSRF-TOKEN": $('#csrf-token').attr('content')
                }
            });
        </script>
          <script type="text/javascript" src="farbtastic.js"></script>
        @stack('scripts')
        <script src="{{asset('assets/backend/libs/sweetalert2/sweetalert2.min.js')}}"></script>
        <!-- App js -->
        <script src="{{asset('assets/backend/js/app.js')}}"></script>
    </body>
</html>
