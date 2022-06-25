@extends('layouts.frontend.master')

@section('title')
    {{ __($pageTitle) }}
@endsection

@push('styles')
    <style>
        .parsley-required,
        .parsley-equalto {
            color: red;
        }

        .parsley-errors-list li {
            color: red;
        }
    </style>
@endpush

@section('content')
    <div class="main-container charity-page">
        <x-banner :banner-title="$bannerTitle"></x-banner>
        <section class="charity py-5">
            <div class="container">

                <div class="row">
                    @if (Session::has('message'))
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="alert alert-{{ Session::get('type') }} alert-dismissible fade show" role="alert">
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

                <form id="charity_form" action="{{ route('front.process.charity.donation') }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="donation_amount">Donation Amount <i class="text-danger">*</i></label>
                                </div>
                                <div class="col-lg-9">
                                    <select name="donation_amount" id="donation_amount" class="form-select" required>
                                        <option value="" selected disabled>Donation Amount</option>
                                        <option value="5">$5</option>
                                        <option value="10">$10</option>
                                        <option value="15">$15</option>
                                        <option value="20">$20</option>
                                        <option value="custom">Custom Amount</option>
                                    </select>
                                    <div class="custom_donation_amount"></div>
                                    <div class="invalid-feedback">
                                        Please select amount / enter valid amount
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-3">
                                    <label for="recurrence">Recurrence <i class="text-danger">*</i></label>
                                </div>
                                <div class="col-lg-9">
                                    <select name="recurrence" id="recurrence" class="form-select" required>
                                        <option value="single">Single Donation</option>
                                        <option value="recurring">Recurring Donation</option>
                                    </select>
                                    <small class="text-white">Single Donation or Recursively charge every month</small>

                                    <div class="invalid-feedback">
                                        Please select recurrence
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-3">
                                    <label for="processing_fees">Processing Fees </label>
                                </div>
                                <div class="col-lg-9">
                                    <select name="processing_fees" id="processing_fees" class="form-select" required>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <small class="text-white">Would you like to cover the processing fees?</small>
                                    <div class="invalid-feedback">
                                        Please select processing fees
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-3">
                                    <label for="behalf_of">Donate on behalf of:</label>
                                </div>
                                <div class="col-lg-9">
                                    <select name="behalf_of" id="behalf_of" class="form-select" required>
                                        <option value="individual">Individual</option>
                                        <option value="company">Company</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select donate on behalf of
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-3">
                                    <label for="message">Message for us</label>
                                </div>
                                <div class="col-lg-9">
                                    <textarea name="message" id="message" class="form-control" rows="6" placeholder="Write us message"></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <label class="mb-0">Donor Details</label>
                                    <hr class="text-white mt-0" />
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <label for="first_name">First Name <i class="text-danger">*</i></label>
                                    <input type="text" name="first_name" id="first_name" placeholder="First Name"
                                        required />
                                    <div class="invalid-feedback">
                                        Please enter first name
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="last_name">Last Name <i class="text-danger">*</i></label>
                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name"
                                        required />
                                    <div class="invalid-feedback">
                                        Please enter last name
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="email">Email <i class="text-danger">*</i></label>
                                    <input type="email" name="email" id="email" placeholder="Email address"
                                        required />
                                    <div class="invalid-feedback">
                                        Please enter valid email address
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" placeholder="Address" />
                                </div>
                                <div class="col-lg-4">
                                    <label for="postal_code">Post Code <i class="text-danger">*</i></label>
                                    <input type="number" min="0" name="postal_code" id="postal_code"
                                        placeholder="Postal Code" required />
                                    <div class="invalid-feedback">
                                        Please enter valid postal code
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <label class="mb-0">Payment Details</label>
                                    <hr class="text-white mt-0" />
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-4">
                                    <label for="card_number">Card Number<i class="text-danger">*</i></label>
                                    <input required="required" name="payment_details[card_number]" type="tel"
                                        class="input-mask" data-inputmask="'mask': '9999 9999 9999 9999'">
                                    <div class="invalid-feedback">
                                        Please enter valid card number
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="cvv">CVC <i class="text-danger">*</i></label>
                                    <input required="required" name="payment_details[cvv]" id="cvv"
                                        class="input-mask" data-inputmask="'mask': '999'" type="tel">
                                    <div class="invalid-feedback">
                                        Please enter cvv
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="expiry_date">Card Expiry Date <i class="text-danger">*</i></label>
                                    <input required="required" name="payment_details[expiry_date]" id="expiry_date"
                                        class="input-mask" data-inputmask="'alias': 'datetime'"
                                        data-inputmask-inputformat="mm/yyyy" type="tel">
                                    <div class="invalid-feedback">
                                        Please enter valid expiry date
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-3">
                                    <label for="payment_method">Payment Method <i class="text-danger">*</i></label>
                                </div>
                                <div class="col-lg-9">
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="" selected disabled>Payment Method</option>
                                        <option value="online">Online Payment</option>
                                    </select>
                                    <div class="check_details"></div>
                                    <div class="invalid-feedback">
                                        Please select payment method / check details
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="links mt-5 text-center">
                                        <button type="submit">Donate Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>

                </form>
            </div>
        </section>
        <x-footer />
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
    </script>
    <script src="{{ asset('assets/frontend/js/charity-script.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
    <!-- form mask -->
    <script src="{{ asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- form mask init -->
    <script src="{{ asset('assets/backend/js/pages/form-mask.init.js') }}"></script>
@endpush
