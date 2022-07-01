@extends('layouts.backend.master')
@section('title')
    {{ __($page_title ?? '-') }}
@endsection
@section('page-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ __($page_title ?? '-') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __($section ?? '-') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __($page_title ?? '-') }}</li>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <div class="col-sm-12 mb-2">
                    {!! $shortcut_buttons !!}
                </div>
                <form class="needs-validation" method="POST" action="{{ route('subscriptions.update', $subs->id) }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        @method('PUT')
                        @csrf
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Name here" value="{{ $subs->name ?? '' }}" required />
                                                <div class="invalid-feedback">
                                                    Please enter valid name.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="price_per_month" class="control-label">Price /
                                                            Month</label>
                                                        <input type="text" class="form-control input-mask text-start"
                                                            name="price_per_month" id="price_per_month"
                                                            placeholder="Price Per Month"
                                                            data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'"
                                                            required value="{{$subs->price_per_month ?? 0}}"/>
                                                        <div class="invalid-feedback">
                                                            Please enter valid amount.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="discount_per_month" class="control-label">Discounted
                                                            Price / Month</label>
                                                        <input type="text" class="form-control input-mask text-start"
                                                            name="discount_per_month" id="discount_per_month"
                                                            placeholder="Discounted Price Per Month"
                                                            data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" value="{{$subs->discount_per_month ?? 0}}" />
                                                        <div class="invalid-feedback">
                                                            Please enter valid discounted amount.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="price_per_year" class="control-label">Price /
                                                            Year</label>
                                                        <input type="text" class="form-control input-mask text-start"
                                                            name="price_per_year" id="price_per_year"
                                                            placeholder="Price Per Year"
                                                            data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'"
                                                            required value="{{$subs->price_per_year ?? 0}}" />
                                                        <div class="invalid-feedback">
                                                            Please enter valid amount.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="discount_per_year" class="control-label">Discounted
                                                            Price / Year</label>
                                                        <input type="text" class="form-control input-mask text-start"
                                                            name="discount_per_year" id="discount_per_year"
                                                            placeholder="Discounted Price Per Year"
                                                            data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" value="{{$subs->discount_per_month ?? 0}}"/>
                                                        <div class="invalid-feedback">
                                                            Please enter valid discounted amount.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea rows="4" class="form-control" name="description" id="description" placeholder="Description here">{!! old('description', json_decode($subs->description)) !!}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid description.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div> <!-- end col -->
                        {{-- <div class="col-sm-3">
                            <div class="card mt-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="image" class="control-label">Icon image</label>
                                                <input type="file" class="form-control" name="image"
                                                    id="image" />
                                                <div class="invalid-feedback">
                                                    Please upload icon image.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Current Icon</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <img src="{{ asset('assets/frontend/images/subscriptions/' . Str::of($subs->icon)->replace(' ', '%20')) }}" class="form-control img-thumbnail mh-400" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">UPDATE SUBSCRIPTION</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">UPDATE SUBSCRIPTION</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/backend/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/pages/form-mask.init.js') }}"></script>
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
        CKEDITOR.dtd.$removeEmpty.span = false;
        CKEDITOR.dtd.$removeEmpty.i = false;
    </script>
@endpush
