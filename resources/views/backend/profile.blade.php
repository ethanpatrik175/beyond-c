@extends('layouts.backend.master')
@section('title')
    {{ __('Profile') }}
@endsection
@push('styles')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('page-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ __('Profile') }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Profile') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('Profile') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                @if (Session::has('message'))
                    <div class="col-sm-12">
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
                @endif
                @if ($errors->any())
                    <div class="col-sm-12">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-block-helper me-2"></i>
                                {{ __($error) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="col-sm-12 message"></div>
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">MANAGE PROFILE</h4>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">PERSONAL INFORMATION</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#password" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">CHANGE PASSWORD</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#picture" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                        <span class="d-none d-sm-block">CHANGE PROFILE PICTURE</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 text-muted">
                                <div class="tab-pane active" id="personal" role="tabpanel">
                                    <div class="col-sm-9">
                                        <form class="needs-validation" method="POST"
                                            action="{{ route('general.update.profile.process') }}" novalidate>
                                            @csrf
                                            <div class="row mb-4">
                                                <label for="horizontal-firstname-input"
                                                    class="col-sm-3 col-form-label">First name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control"
                                                        id="horizontal-firstname-input" name="first_name"
                                                        value="{{ $profile->first_name }}" placeholder="First Name"
                                                        required />
                                                    <div class="invalid-feedback">
                                                        Please enter first name.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-lastname-input" class="col-sm-3 col-form-label">Last
                                                    name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="horizontal-lastname-input"
                                                        name="last_name" value="{{ $profile->last_name }}"
                                                        placeholder="Last Name" />
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-email-input"
                                                    class="col-sm-3 col-form-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="email" name="email" class="form-control"
                                                        id="horizontal-email-input" value="{{ $profile->email }}"
                                                        required placeholder="Email Address" />
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-phone-input"
                                                    class="col-sm-3 col-form-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="phone" value="{{ $profile->phone }}"
                                                        class="form-control" id="horizontal-phone-input"
                                                        placeholder="Phone" />
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label for="horizontal-state-input"
                                                    class="col-sm-3 col-form-label">State</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="state" value="{{ $profile->state }}"
                                                        class="form-control" id="horizontal-state-input"
                                                        placeholder="State" />
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label for="horizontal-address-input"
                                                    class="col-sm-3 col-form-label">Address</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="address" value="{{ $profile->address }}"
                                                        class="form-control" id="horizontal-address-input"
                                                        placeholder="Address" />
                                                </div>
                                            </div>

                                            <div class="row justify-content-end">
                                                <div class="col-sm-9">
                                                    <div>
                                                        <button type="submit" class="btn btn-primary w-md">UPDATE</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="password" role="tabpanel">
                                    <div class="col-sm-9">
                                        <form class="needs-validation" method="POST"
                                            action="{{ route('general.change.password.process') }}" novalidate>
                                            @csrf
                                            <div class="row mb-4">
                                                <label for="horizontal-old-password-input"
                                                    class="col-sm-3 col-form-label">Old Password</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control"
                                                        id="horizontal-old-password-input" name="old_password"
                                                        placeholder="Old Password" required />
                                                    <div class="invalid-feedback">
                                                        Please enter old password.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-new-password-input"
                                                    class="col-sm-3 col-form-label">New Password</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control"
                                                        id="horizontal-new-password-input" name="new_password"
                                                        placeholder="New Password" required />
                                                    <div class="invalid-feedback">
                                                        Please enter new password.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-confirm-password-input"
                                                    class="col-sm-3 col-form-label">Confirm Password</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control"
                                                        id="horizontal-confirm-password-input" name="confirm_password"
                                                        placeholder="Old Password" required />
                                                    <div class="invalid-feedback">
                                                        Please enter Confirm password.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-9">
                                                    <div>
                                                        <button type="submit" class="btn btn-primary w-md">UPDATE</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="picture" role="tabpanel">
                                    <div class="col-sm-12">
                                        <form class="needs-validation" method="POST" enctype="multipart/form-data"
                                            action="{{ route('general.change.picture.process') }}" novalidate>
                                            @csrf
                                            <input type="hidden" name="id" value="{{ auth()->user()->id }}" />
                                            <div class="row mb-4">
                                                <label class="col-sm-3 col-form-label">Profile Picture</label>
                                                <div class="col-sm-3">
                                                    @if (isset(auth()->user()->image))
                                                        <img src="{{ asset('assets/backend/images/users/' . auth()->user()->image) }}"
                                                            class="form-control img-thumbnail" alt="">
                                                    @else
                                                        <img src="{{ asset('assets/backend/images/users/user.png') }}"
                                                            class="form-control img-thumbnail" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-image-input"
                                                    class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-9">
                                                    <input type="file" class="form-control" id="horizontal-image-input"
                                                        name="image" required />
                                                    <div class="invalid-feedback">
                                                        Please upload valid image
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-9">
                                                    <div>
                                                        <button type="submit" class="btn btn-primary w-md">UPDATE</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                            </h4>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    @if (isset(auth()->user()->image))
                                        <img src="{{ asset('assets/backend/images/users/' . auth()->user()->image) }}"
                                            class="form-control img-thumbnail" alt="">
                                    @else
                                        <img src="{{ asset('assets/backend/images/users/user.png') }}"
                                            class="form-control img-thumbnail" alt="">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
@endsection
