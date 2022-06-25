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
                <form class="needs-validation create-form" method="POST" action="{{ route('venues.update', $venue->id) }}"
                    enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-sm-10 mb-2">
                            {!! $buttons !!}
                        </div>
                        <div class="col-sm-2 text-end"><button type="submit" class="btn btn-sm btn-primary">UPDATE
                                {{ strtoupper(Str::singular($section)) }}</button></div>
                    </div>
                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" id="name"
                                                    placeholder="Title" value="{{ $venue->title ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="sub_title" class="form-label">Sub Title</label>
                                                <input type="text" class="form-control" name="sub_title" id="sub_title"
                                                    placeholder="Sub Title" value="{{ $venue->sub_title ?? '' }}" />
                                                <div class="invalid-feedback">
                                                    Please enter valid sub title.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug" id="slug"
                                                    placeholder="Slug" value="{{ $venue->slug ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid slug.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea rows="2" class="form-control" name="address" id="address"
                                                    placeholder="Address" required>{{ json_decode($venue->address) ?? '' }}</textarea>
                                                <div class="invalid-feedback">
                                                    Please enter valid address.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <input type="text" class="form-control" name="longitude" id="longitude"
                                                    placeholder="Longitude" value="{{ $venue->longitude ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid longitude.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <input type="text" class="form-control" name="latitude" id="latitude"
                                                    placeholder="Latitude" value="{{ $venue->latitude ?? '' }}" required>
                                                <div class="invalid-feedback">
                                                    Please enter valid latitude.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea rows="4" class="form-control" name="description" id="description" placeholder="Description here"
                                                    required>{!! json_decode($venue->description) ?? '' !!}</textarea>
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
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label for="image" class="control-label">Icon image</label>
                                                <input type="file" class="form-control" name="image" id="image" />
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
                                                <label for="image" class="control-label">Current Icon</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <img src="{{ asset('assets/frontend/images/venues/' . Str::of($venue->image)->replace(' ', '%20')) }}" class="form-control img-thumbnail mh-400" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-5">
                            <button type="submit" class="btn btn-primary">UPDATE {{strtoupper(Str::singular($section))}}</button>
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
    <script src="{{ asset('assets/backend/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/backend/js/create-slug.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
        $(document).on('click', '#submit-form', function() {
            $(document).find('form.create-form').submit();
        });
    </script>
@endpush
