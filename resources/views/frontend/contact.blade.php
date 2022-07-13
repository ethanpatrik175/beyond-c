@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@section('content')
    <div class="main-container contact-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="contact-us section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading text-width text-lg-center">
                            <h1 class="text-white">{{(isset($section_1)) ? $section_1->sectioncontent[0]->content : ''}}</h1>
                            <p>{{ (isset($section_1)) ? $section_1->sectioncontent[1]->content : ''}} </p>
                        </div>
                    </div>
                </div>
                <div>

                    <div class="row">
                        @if (Session::has('message'))
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show"
                                    role="alert">
                                    @if (Session::get('type') == 'danger')
                                        <i class="mdi mdi-block-helper me-2"></i>
                                    @else
                                        <i class="mdi mdi-check-all me-2"></i>
                                    @endif
                                    {{ __(Session::get('message')) }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <div class="col-sm-2"></div>
                        @endif
                        @if ($errors->any())
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-block-helper me-2"></i>
                                        {{ __($error) }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-sm-2"></div>
                        @endif
                    </div>

                    {{-- <form method="POST" action="{{ route('contact-us.store') }}" class="needs-validation" novalidate> --}}
                    <form method="POST" action="{{ route('store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row mt-md-5 mt-2">
                            <div class="col-lg-6">
                                <input type="text" name="name" placeholder="Your Name*" required>
                                <div class="invalid-feedback">
                                    Please enter valid name.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="email" placeholder="Your Email*" required>
                                <div class="invalid-feedback">
                                    Please enter valid email.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="lastname" placeholder="Last Name*" class="mt-4" required>
                                <div class="invalid-feedback">
                                    Please enter valid lastname.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" name="number" placeholder="number*" class="mt-4" required>
                                <div class="invalid-feedback">
                                    Please enter valid number.
                                </div>
                            </div>

                        </div>
                        <div class="row mt-md-5">
                            <div class="col-lg-12 ">
                                <textarea name="message" id="" cols="30" rows="10" placeholder="Your Message" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter valid message.
                                </div>
                            </div>
                        </div>
                        <div class="links mt-4 text-center">
                            <button type="submit">Submit Now</button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
        <section class="map">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 px-0">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387190.27988647175!2d-74.25986673512958!3d40.697670068477386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1653423760372!5m2!1sen!2s"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endpush
